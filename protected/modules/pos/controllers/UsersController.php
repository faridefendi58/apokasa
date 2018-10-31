<?php

class UsersController extends EController
{
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
				'actions'=>array('create','priviledge'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
				//'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('index','view'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('update'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete'),
				'expression'=>'Rbac::ruleAccess(\'delete_p\')',
			),
			array('allow',
				'actions'=>array('viewemployee'),
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
		Yii::app()->clientScript->registerCoreScript('jquery');
		$model=new User('create');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->salt=md5($model->generateSalt());
			$model->date_entry=date(c);
			$model->user_entry=Yii::app()->user->id;
			$model->status=1;
			if($model->save()){
				$model->password=md5($model->salt.$model->password);
				if($model->update(array('password')))
					$this->redirect(array('priviledge','id'=>$model->id, 'group'=>$model->group_id));
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
		Yii::app()->clientScript->registerCoreScript('jquery');
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->date_update=date(c);
			$model->user_update=Yii::app()->user->id;
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
			if($this->loadModel($id)->delete()){
				$del=RbacUserAccess::model()->deleteAllByAttributes(array('user_id'=>$id));
			}

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
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('view',array(
			'model'=>$model,
		));
	}
	
	public function actionPriviledge()
	{
		$dataProvider=Crawler::getDataProvider(Yii::app()->params['exceptionRbacAccess'],true);
		
		foreach($dataProvider as $data){
			$num[]=count(Crawler::getInternalActions($data['controller'].'Controller'));
		}
		rsort($num);
		$model=new RbacUserAccess;
		if(isset($_POST['RbacUserAccess'])){
			$model->attributes=$_POST['RbacUserAccess'];
			//var_dump($model->access); exit;
			foreach($model->access as $module=>$access){
				/* foreach($access as $controller=>$access2){
					foreach($access2 as $action=>$status){
						$this->simpan($module,$controller,$action,$_GET['id'],$status);
						Yii::app()->user->setState('user_access',$model->listAccess($_GET['id']));
					}
				} */
				foreach($access as $controller=>$priv){
					$this->simpan($module,$controller,$_GET['id'],$priv);
				}
			}
			Yii::app()->user->setFlash('userrbac','Data berhasil disimpan.');
			$this->refresh();
		}
		
		if(isset($_GET['group']))
			$group=$_GET['group'];
		else
			$group=0;
		$this->render('priviledge',array(
				'dataProvider'=>$dataProvider,
				'model'=>$model,
				'num_column'=>$num[0],
				'group'=>$group,
				'user'=>User::model()->findByPk($_GET['id']),
			));
	}
	
	/* private function simpan($module,$controller,$action,$user_id,$status)
	{
		$model=new RbacUserAccess;
		$model->module=strtolower($module);
		$model->controller=strtolower($controller);
		$model->action=strtolower($action);
		$model->user_id=(int)$user_id;
		$model->status=$status;
		
		$criteria=new CDbCriteria;
		$criteria->compare('module',$model->module);
		$criteria->compare('controller',$model->controller);
		$criteria->compare('action',$model->action);
		$criteria->compare('user_id',$model->user_id);
		$count=RbacUserAccess::model()->count($criteria);
		if($count>0){
			$model2=RbacUserAccess::model()->find($criteria);
			$model2->status=$model->status;
			$model2->update(array('status'));
		}else{
			$model->save();
		}
	} */
	
	private function simpan($module,$controller,$user_id,$priv=array())
	{
		$model=new RbacUserAccess;
		$model->module=strtolower($module);
		$model->controller=strtolower($controller);
		$model->user_id=(int)$user_id;
		
		$criteria=new CDbCriteria;
		$criteria->compare('module',$model->module);
		$criteria->compare('controller',$model->controller);
		$criteria->compare('user_id',$model->user_id);
		$count=RbacUserAccess::model()->count($criteria);
		if($count>0){
			$model2=RbacUserAccess::model()->find($criteria);
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
	
	public function actionViewemployee()
	{
		if( Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$employeeid=Employees::getItemByName($_POST['User']['employee_cr'])->employee_id;
			$criteria=new CDbCriteria;
			$criteria->compare('name',$_POST['User']['employee_cr']);
			
			$dataProvider=new CActiveDataProvider('Employees',array('criteria'=>$criteria));
			
			echo CJSON::encode( array(
				  'status'=>'success',
				  'div' => $this->renderPartial('_view_employee', array('dataProvider'=>$dataProvider),true,true),
				  'employeeid'=> $employeeid,
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
		$model=User::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
