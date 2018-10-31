<?php

class PosModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		//default controller
		$this->defaultController = 'default';
		
		Yii::app()->setComponents(array(
			'errorHandler'=>array(
				'class'=>'CErrorHandler',
				'errorAction'=>$this->getId().'/default/error',
			),
			'user'=>array(
				'class'=>'CWebUser',
				'stateKeyPrefix'=>'pos',
				'loginUrl'=>Yii::app()->createUrl($this->getId().'/default/login'),
				'allowAutoLogin'=>true,
			),
		), false);

		// import the module-level models and components
		$this->setImport(array(
			'pos.models.*',
			'pos.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
