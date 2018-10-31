<?php

class InvoicesController extends EController
{
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
				'actions'=>array('create','addItems'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('view'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('update'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete','deleteItem'),
				'expression'=>'Rbac::ruleAccess(\'delete_p\')',
			),
			array('allow',
				'actions'=>array('refund','printPreview'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionCreate()
	{ 
		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionView()
	{
		$criteria1 = new CDbCriteria;
		$criteria2 = new CDbCriteria;
		$criteria3 = new CDbCriteria;
		$criteria4 = new CDbCriteria;

		$default_range = 'this_month';

		if(isset($_GET['Invoice'])){
			$criteria1->compare('customer_id',$_GET['Invoice']['customer_id']);
			$criteria1->compare('id',$_GET['Invoice']['id']);
			$criteria1->addBetweenCondition('DATE_FORMAT(date_entry,"%Y-%m-%d")',$_GET['Invoice']['date_from'],$_GET['Invoice']['date_to'],'AND');
			$criteria2->compare('customer_id',$_GET['Invoice']['customer_id']);
			$criteria2->compare('id',$_GET['Invoice']['id']);
			$criteria2->addBetweenCondition('DATE_FORMAT(date_entry,"%Y-%m-%d")',$_GET['Invoice']['date_from'],$_GET['Invoice']['date_to'],'AND');
			$criteria3->compare('customer_id',$_GET['Invoice']['customer_id']);
			$criteria3->compare('id',$_GET['Invoice']['id']);
			$criteria3->addBetweenCondition('DATE_FORMAT(date_entry,"%Y-%m-%d")',$_GET['Invoice']['date_from'],$_GET['Invoice']['date_to'],'AND');
			$criteria4->compare('customer_id',$_GET['Invoice']['customer_id']);
			$criteria4->compare('id',$_GET['Invoice']['id']);
			$criteria4->addBetweenCondition('DATE_FORMAT(date_entry,"%Y-%m-%d")',$_GET['Invoice']['date_from'],$_GET['Invoice']['date_to'],'AND');

			if (isset($_GET['Invoice']['range'])) {
                $default_range = $_GET['Invoice']['range'];
			    switch ($_GET['Invoice']['range']) {
                    case "today":
                        $criteria1->addInCondition('DATE_FORMAT(date_entry, "%Y-%m-%d")', array(date("Y-m-d")), 'AND');
                        $criteria4->addInCondition('DATE_FORMAT(date_entry, "%Y-%m-%d")', array(date("Y-m-d")), 'AND');
                        break;
                    case "this_week":
                        $criteria1->addBetweenCondition(
                            'DATE_FORMAT(date_entry,"%Y-%m-%d")',
                            date( 'Y-m-d', strtotime( 'monday this week' ) ),
                            date( 'Y-m-d', strtotime( 'sunday this week' ) ),
                            'AND'
                        );
                        $criteria4->addBetweenCondition(
                            'DATE_FORMAT(date_entry,"%Y-%m-%d")',
                            date( 'Y-m-d', strtotime( 'monday this week' ) ),
                            date( 'Y-m-d', strtotime( 'sunday this week' ) ),
                            'AND'
                        );
                        break;
                    case "this_month":
                        $criteria1->addInCondition('DATE_FORMAT(date_entry, "%Y-%m")', array(date("Y-m")), 'AND');
                        $criteria4->addInCondition('DATE_FORMAT(date_entry, "%Y-%m")', array(date("Y-m")), 'AND');
                        break;
                    case "last_month":
                        $criteria1->addInCondition('DATE_FORMAT(date_entry, "%Y-%m")', array(date('Y-m', strtotime(date('Y-m')." -1 month"))), 'AND');
                        $criteria4->addInCondition('DATE_FORMAT(date_entry, "%Y-%m")', array(date('Y-m', strtotime(date('Y-m')." -1 month"))), 'AND');
                        break;
                    case "this_year":
                        $criteria1->addInCondition('DATE_FORMAT(date_entry, "%Y")', array(date("Y")), 'AND');
                        $criteria4->addInCondition('DATE_FORMAT(date_entry, "%Y")', array(date("Y")), 'AND');
                        break;
                }
            }
		} else {
            $criteria1->addInCondition('DATE_FORMAT(date_entry, "%Y-%m")', array(date("Y-m")), 'AND');
            $criteria4->addInCondition('DATE_FORMAT(date_entry, "%Y-%m")', array(date("Y-m")), 'AND');
        }

		$criteria1->order = 'date_entry DESC';
		$dataProvider = new CActiveDataProvider('Invoice',array('criteria'=>$criteria1));
        $dataProvider->model->range = $default_range;

		/*$criteria2->compare('status',0);
		$criteria2->order='date_entry DESC';
		$unpaidProvider=new CActiveDataProvider('Invoice',array('criteria'=>$criteria2));

		$criteria3->compare('status',1);
		$criteria3->order='date_entry DESC';
		$paidProvider=new CActiveDataProvider('Invoice',array('criteria'=>$criteria3));*/

        $unpaidProvider = null;
        $paidProvider = null;

		$criteria4->compare('status',2);
		$criteria4->order='date_entry DESC';
		$refundProvider=new CActiveDataProvider('Invoice',array('criteria'=>$criteria4));

		$this->render('view',array(
			'dataProvider'=>$dataProvider,
			'unpaidProvider'=>$unpaidProvider,
			'paidProvider'=>$paidProvider,
			'refundProvider'=>$refundProvider,
		));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['Invoice'])){
			$model->attributes=$_POST['Invoice'];
			$model->date_update=date(c);
			$model->user_update=Yii::app()->user->id;
			if($model->save()){
				Yii::app()->user->setFlash('update',Yii::t('global','Your data is successfully saved.'));
				$this->refresh();
			}
		}

		$criteria=new CDbCriteria;
		$criteria->compare('invoice_id',$model->id);
		$itemsProvider=new CActiveDataProvider('InvoiceItem',array('criteria'=>$criteria));

		$this->render('update',array('model'=>$model,'itemsProvider'=>$itemsProvider));
	}

	public function actionAddItems($id)
	{
		if(Yii::app()->request->isPostRequest)
		{	
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model=new InvoiceItem;
			$model->invoice_id=$id;
			if(isset($_POST['InvoiceItem'])){
				$model->attributes=$_POST['InvoiceItem'];
				$model->invoice_id=$id;
				$model->type='order';
				$product=Product::item($model->product_id);
				$model->title=$product->name;
				$model->price=$product->price->sold_price*$model->quantity;
				$model->date_entry=date(c);
				$model->user_entry=Yii::app()->user->id;
				//create also the order
				$model2=new Order;
				$model2->product_id=$model->product_id;
				$model2->title=$product->name;
				$model2->invoice_id=$id;
				$model2->quantity=$model->quantity;
				$model2->price=$product->price->sold_price;
				$model2->group_id=Order::getGroupByInvoice($id);
				$model2->type=1;
				$model2->status=1;
				$model2->notes='Tambahan item';
				$model2->date_entry=date(c);
				$model2->user_entry=Yii::app()->user->id;
				if($model2->save()){
					$model->rel_id=$model2->id;
					if($model->save()){
						echo CJSON::encode(array('status'=>'success'));
						exit;
					}else{
						$model2->delete();
						echo CJSON::encode(array('status'=>'failed'));
					}
				}
			}
			echo CJSON::encode(array(
				'status'=>'success',
				'div'=>$this->renderPartial('_add_items',array('model'=>$model),true,true),
			));
			exit;
		}
	}

	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{	
			// we only allow deletion via POST request
			$model=Invoice::model()->findByPk($id);
			if($model->delete()){
				InvoiceItem::model()->deleteAllByAttributes(array('invoice_id'=>$id));
			}

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionDeleteItem($id)
	{
		if(Yii::app()->request->isPostRequest)
		{	
			// we only allow deletion via POST request
			$model=InvoiceItem::model()->findByPk($id);
			$order=Order::model()->findByPk($model->rel_id);
			if($model->delete()){
				$order->delete();
			}

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Invoice::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function actionRefund($id)
	{
		if(Yii::app()->request->isPostRequest)
		{	
			$model=Invoice::model()->findByPk($id);
			$model->status=3;
			$model->date_update=date(c);
			$model->user_update=Yii::app()->user->id;
			if($model->save()){
			echo CJSON::encode(array(
				'status'=>'success',
				'div'=>Lookup::item('InvoiceStatus',$model->status),
			));
			exit;
			}
		}
	}

	public function actionPrintPreview($id)
	{
		if(Yii::app()->request->isPostRequest)
		{	
			$model=Invoice::model()->findByPk($id);
			$print=false;
			if(isset($_POST['new_order']))
				$print=true;
			echo CJSON::encode(array(
				'status'=>'success',
				'div'=>$this->renderPartial('_print_preview',array('model'=>$model,'print'=>$print),true,true),
				'invoice_number'=>$model->invoiceFormatedNumber,
			));
			exit;
		}
	}
}
