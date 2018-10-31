<?php

class ProductsController extends EController
{
	public static $_alias='Product';

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('view'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('create', 'addDiscount'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('update'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete', 'deleteDiscount'),
				'expression'=>'Rbac::ruleAccess(\'delete_p\')',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Product;
		$model2=new ProductPrice;
		$model2->current_stock=100;
        $model3 = new ProductDiscount();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Product']))
		{
			$model->attributes=$_POST['Product'];
			$model->barcode=1;
			if(!empty($model->name)){
				$explode=explode(" ",strtolower($model->name));
				$model->tag=serialize($explode);
			}			
			$model->status=1;
			$model->date_entry=date(c);
			$model->user_entry=Yii::app()->user->id;
			if($model->save()){
				$nol=str_repeat('0',4-strlen($model->id));
				$model->barcode=$model->type.$nol.$model->id;
				$model->update('barcode');
				$model2->attributes=$_POST['ProductPrice'];
				$model2->product_id=$model->id;
				if(strtotime($model2->purchase_date)==0)
					$model2->purchase_date=date(c);
				$model2->date_entry=date(c);
				$model2->user_entry=Yii::app()->user->id;
				$model2->save();

				Yii::app()->user->setFlash('create',Yii::t('global','Your data has been saved successfully.'));
				$this->refresh();
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'model2'=>$model2,
            'model3' => $model3
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$model->tag=unserialize($model->tag);
		if(is_array($model->tag))
			$model->tag=implode(", ",$model->tag);

		$model2=$model->price;
		if(empty($model2))
			$model2=new ProductPrice;

		$model3 = new ProductDiscount();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Product']))
		{
			$model->attributes=$_POST['Product'];
			if(!empty($model->tag)){
				$explode=explode(", ",strtolower($model->tag));
				$model->tag=serialize($model->tag);
			}
			$model->date_update=date(c);
			$model->user_update=Yii::app()->user->id;
			if($model->save()){
				$nol=str_repeat('0',4-strlen($model->id));
				$model->barcode=$model->type.$nol.$model->id;
				$model->update('barcode');
				$model2->attributes=$_POST['ProductPrice'];
				if($model2->isNewRecord){
					$model2->product_id=$model->id;
					if(strtotime($model2->purchase_date)==0)
						$model2->purchase_date=date(c);
					$model2->date_entry=date(c);
					$model2->user_entry=Yii::app()->user->id;
					$model2->save();
				}else{
					$model2->date_update=date(c);
					$model2->user_update=Yii::app()->user->id;
					$model2->save();
				}

				if (isset($_POST['ProductDiscount']) && count($_POST['ProductDiscount']) > 0) {
                    foreach ($_POST['ProductDiscount']['quantity'] as $discount_id => $discount_value) {
                        if (!empty($_POST['ProductDiscount']['id'][$discount_id])) {
                            $pd_model = ProductDiscount::model()->findByPk($_POST['ProductDiscount']['id'][$discount_id]);
                        } else {
                            $pd_model = new ProductDiscount();
                            $pd_model->product_id = $model->id;
                        }

                        $pd_model->quantity = (int)$discount_value;
                        $pd_model->quantity_max = (int)$_POST['ProductDiscount']['quantity_max'][$discount_id];
                        $pd_model->base_price = (int)$_POST['ProductDiscount']['base_price'][$discount_id];
                        $pd_model->date_start = date("Y-m-d", strtotime($_POST['ProductDiscount']['date_start'][$discount_id]));
                        $pd_model->date_end = date("Y-m-d", strtotime($_POST['ProductDiscount']['date_end'][$discount_id]));
                        $pd_model->save();
                    }
                }
				Yii::app()->user->setFlash('update',Yii::t('global','Your data has been saved successfully.'));
				$this->refresh();
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'model2'=>$model2,
            'model3' => $model3
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Manages all models.
	 */
	public function actionView()
	{
		$model=new Product('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Product']))
			$model->attributes=$_GET['Product'];

		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Product::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='promo-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionAddDiscount()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false;

            $model = new ProductDiscount();

            echo CJSON::encode(array(
                'status' => 'success',
                'div' => $this->renderPartial('_discount', array('model' => $model), true, true)
            ));
            exit;
        }
    }

    public function actionDeleteDiscount($id)
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

            $model = ProductDiscount::model()->findByPk($id);
            if ($model->delete()) {
                echo CJSON::encode(array('status' => 'success'));
            } else {
                echo CJSON::encode(array('status' => 'failed'));
            }

            exit;
        }
    }
}
