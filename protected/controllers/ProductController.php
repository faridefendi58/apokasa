<?php

class ProductController extends DController
{
	public $layout='column1';

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}/**
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
			array('allow',  // allow all users to access 'index' and 'view' actions.
				'actions'=>array('view','detail','getProduct','getProductItems'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionView($id)
	{
		if(Yii::app()->request->isAjaxRequest){
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$criteria=new CDbCriteria;
			$criteria->compare('parent_id',$id);
			$criteria->limit=4;
			$dataProvider=new CActiveDataProvider('ProductItems',array('criteria'=>$criteria,'pagination'=>false));
			echo CJSON::encode( array(
				'status'=>'success',
				'type'=>Product::model()->findByPk($id)->type,
				'div' => $this->renderPartial('_view',array('dataProvider'=>$dataProvider),true,true),
			));
			exit;
		}
	}

	public function actionDetail($id)
	{
		if(Yii::app()->request->isAjaxRequest){
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model=Product::model()->findByPk($id);
			$criteria=new CDbCriteria;
			$criteria->compare('product_id',$id);
			$criteria->compare('image_one_rel.id','>0');
			$criteria->limit=4;
			$criteria->with=array('image_one_rel');
			$dataProvider=new CActiveDataProvider('ProductItems',array('criteria'=>$criteria,'pagination'=>false));
			echo CJSON::encode( array(
				'status'=>'success',
				'type'=>$model->type,
				'div' => $this->renderPartial('_detail',array('dataProvider'=>$dataProvider),true,true),
			));
			exit;
		}
	}

	public function actionGetProduct()
	{
		if(Yii::app()->request->isAjaxRequest){
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model=new ProductOrders;
			$items=Product::items($_POST['type'],'Sub Category Product');
			echo CJSON::encode( array(
				'status'=>'success',
				'div' => CHtml::activeDropDownList($model,'product_id',$items,array('class'=>'form-control')),
			));
			exit;
		}
	}

	public function actionGetProductItems()
	{
		if(Yii::app()->request->isAjaxRequest){
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model=new ProductOrders;
			if(isset($_POST['product_item_id'])){
				$items=ProductItems::items($_POST['product_id'],10,'Product Name',1);
				$attr='product_sub_item_id';
			}else{
				$items=ProductItems::items($_POST['product_id'],10,'Product Type');
				$attr='product_item_id';
			}
			echo CJSON::encode( array(
				'status'=>'success',
				'div' => CHtml::activeDropDownList($model,$attr,$items,array('class'=>'form-control','rel_id'=>$_POST['product_id'])),
				'level'=>($attr=='product_sub_item_id')? 1:0,
			));
			exit;
		}
	}

	public function actionTerimakasih()
	{
		$this->layout='column_thanks';
		if(Yii::app()->user->hasState('terimakasih')){
			$pesan = Yii::app()->user->getState('terimakasih');
			Yii::app()->user->setState('terimakasih',null);
			$this->render('terimakasih',array('pesan'=>$pesan));
		}else
			$this->redirect(array('/site/home'));
	}
}
