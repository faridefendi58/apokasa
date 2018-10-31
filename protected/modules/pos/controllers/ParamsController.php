<?php

class ParamsController extends EController
{
	public static $_alias='Parameter';

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
				'actions'=>array('create'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('update'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete'),
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
		$model=new PParams;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PParams']))
		{
			$model->attributes=$_POST['PParams'];
			if(!empty($_FILES['PParams']['name']['image'])){
				$model->value=serialize($_FILES['PParams']['name']['image']);
				$model->type=3;
			}else{
				$model->value=serialize($model->value);
				$model->type=1;
			}
			$pecah=explode(" ", strtolower($model->params_name));
			$model->key=implode("_",$pecah);
			//var_dump($model->attributes);exit;
			if($model->save()){
				if(!empty($_FILES['PParams']['name']['image'])){
					$uploaddir = 'uploads/images/';
					$uploadfile = $uploaddir . basename($_FILES['PParams']['name']['image']);
					move_uploaded_file($_FILES['PParams']['tmp_name']['image'], $uploadfile);
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
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
		$model->value=unserialize($model->value);
		$image_lama=$model->value;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PParams']))
		{
			$model->attributes=$_POST['PParams'];
			if(!empty($_FILES['PParams']['name']['image']))
				$model->value=$_FILES['PParams']['name']['image'];
				
			if($model->validate()){
				$pecah=explode(" ", strtolower($model->params_name));
				$model->key=implode("_",$pecah);
				Yii::app()->config->set($model->key, $model->value);
				if(!empty($_FILES['PParams']['name']['image'])){
					$delfile = $uploaddir . basename($image_lama);
					if(is_file($delfile))
						unlink ($delfile);
					$uploaddir = 'uploads/images/';
					$uploadfile = $uploaddir . basename($_FILES['PParams']['name']['image']);
					move_uploaded_file($_FILES['PParams']['tmp_name']['image'], $uploadfile);
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
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
		$model=new PParams('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PParams']))
			$model->attributes=$_GET['PParams'];

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
		$model=PParams::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='params-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
