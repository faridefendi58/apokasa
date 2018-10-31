<?php

class RbacGroupController extends EController
{

	public static $_alias='Group Role Based Access Control';

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
				'actions'=>array('create','priviledge','addgroup'),
				//'expression'=>'Rbac::ruleAccess(\'create_p\')',
				'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('view'),
				//'expression'=>'Rbac::ruleAccess(\'read_p\')',
				'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('update'),
				//'expression'=>'Rbac::ruleAccess(\'update_p\')',
				'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('delete'),
				//'expression'=>'Rbac::ruleAccess(\'delete_p\')',
				'users'=>array('@'),
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
		$model=new RbacGroup('create');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RbacGroup']))
		{
			$model->attributes=$_POST['RbacGroup'];
			if($model->save())
				$this->redirect(array('priviledge','id'=>$model->id));
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RbacGroup']))
		{
			$model->attributes=$_POST['RbacGroup'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		$model=new RbacGroup('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['RbacGroup']))
			$model->attributes=$_GET['RbacGroup'];

		$this->render('view',array(
			'model'=>$model,
		));
	}

	public function actionPriviledge()
	{
		$this->layout='column2';

		$dataProvider=Crawler::getDataProvider(Yii::app()->params['exceptionRbacAccess'],true);
		
		foreach($dataProvider as $data){
			$num[]=count(Crawler::getInternalActions($data['controller'].'Controller'));
		}
		if(is_array($num))
			rsort($num);
		$model=new RbacGroupAccess;
		if(isset($_POST['RbacGroupAccess'])){
			$model->attributes=$_POST['RbacGroupAccess'];
			foreach($model->access as $module=>$access){
				/* foreach($access as $controller=>$access2){
					foreach($access2 as $action=>$status){
						$this->simpan($module,$controller,$action,$_GET['id'],$status);
					}
				} */
				foreach($access as $controller=>$priv){
					$this->simpan($module,$controller,$_GET['id'],$priv);
				}
			}
			Yii::app()->user->setFlash('grouprbac','Data berhasil disimpan.');
			$this->refresh();
		}
		$this->render('priviledge',array('dataProvider'=>$dataProvider,'model'=>$model,'num_column'=>$num[0]));
	}
	
	/* private function simpan($module,$controller,$action,$group_id,$status)
	{
		$model=new RbacGroupAccess;
		$model->module=strtolower($module);
		$model->controller=strtolower($controller);
		$model->action=strtolower($action);
		$model->group_id=(int)$group_id;
		$model->status=$status;
		
		$criteria=new CDbCriteria;
		$criteria->compare('module',$model->module);
		$criteria->compare('controller',$model->controller);
		$criteria->compare('action',$model->action);
		$criteria->compare('group_id',$model->group_id);
		$count=RbacGroupAccess::model()->count($criteria);
		if($count>0){
			$model2=RbacGroupAccess::model()->find($criteria);
			$model2->status=$model->status;
			$model2->update(array('status'));
		}else{
			$model->save();
		}
	} */
	
	private function simpan($module,$controller,$group_id,$priv=array())
	{
		$model=new RbacGroupAccess;
		$model->module=strtolower($module);
		$model->controller=strtolower($controller);
		$model->group_id=(int)$group_id;
		
		$criteria=new CDbCriteria;
		$criteria->compare('module',$model->module);
		$criteria->compare('controller',$model->controller);
		$criteria->compare('group_id',$model->group_id);
		$count=RbacGroupAccess::model()->count($criteria);
		if($count>0){
			$model2=RbacGroupAccess::model()->find($criteria);
			$model2->create_p=$priv['create_p'];
			$model2->read_p=$priv['read_p'];
			$model2->update_p=$priv['update_p'];
			$model2->delete_p=$priv['delete_p'];
			$model2->update(array('create_p','update_p','delete_p','read_p'));
		}else{
			$model->create_p=$priv['create_p'];
			$model->read_p=$priv['read_p'];
			$model->update_p=$priv['update_p'];
			$model->delete_p=$priv['delete_p'];
			$model->save();
		}
	}
	
	public function actionAddgroup()
	{
		$model=new RbacGroup;
		
		if(isset($_POST['RbacGroup']))
		{
			$model->attributes=$_POST['RbacGroup'];
			if($model->save()){
				if(Yii::app()->request->isAjaxRequest){
					$model2=new User;
					echo CJSON::encode( array(
							'status'=>'success',
							'div'=>'<div class="flash-success">Your data is succesfully saved.</div>',
							'list'=>CHtml::activeDropDownList($model2,'group_id',$model->items()),
					));
					exit;
				}else{
					$this->redirect(array('create'));
				}
			}else{
				if(Yii::app()->request->isAjaxRequest){
					echo CJSON::encode( array(
							'status'=>'error',
							'div'=>$this->renderPartial('/rbacGroup/_form', array('model'=>$model),true,true),
						));
					exit;
				}
			}
		}
		
		if( Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			echo CJSON::encode( array(
				  'status'=>'failure',
				  'div' => $this->renderPartial('/rbacGroup/_form', array('model'=>$model),true,true),
				));
			exit;
		}
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=RbacGroup::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='rbac-group-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
