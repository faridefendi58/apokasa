<?php
/*
 * WysiHtml widget class file.
 */
class EWysiHtml extends CInputWidget
{
	public $options=array();
	
	public function init()
	{
		$this->publishAssets();
	}
	
    public function run()
    {
		list($name,$id)=$this->resolveNameID();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		else
			$this->htmlOptions['name']=$name;

		if($this->hasModel())
			echo CHtml::activeTextArea($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::textArea($name,$this->value,$this->htmlOptions);
		
		$options=CJavaScript::encode($this->options);	
		Yii::app()->clientScript->registerScript($id,"
			$('#{$id}').wysihtml5({$options});
		");
	}
	
	protected static function publishAssets()
	{
		$assets=dirname(__FILE__).'/assets';
		$baseUrl=Yii::app()->assetManager->publish($assets);
		if(is_dir($assets)){
			Yii::app()->clientScript->registerCoreScript('jquery');
			//Yii::app()->clientScript->registerScriptFile($baseUrl.'/jquery-1.10.2.min.js',CClientScript::POS_HEAD);
			//Yii::app()->clientScript->registerScriptFile($baseUrl.'/bootstrap.min.js',CClientScript::POS_END);
			Yii::app()->clientScript->registerScriptFile($baseUrl.'/wysihtml5-0.3.0.min.js',CClientScript::POS_END);
			Yii::app()->clientScript->registerScriptFile($baseUrl.'/bootstrap-wysihtml5.js',CClientScript::POS_END);	
			Yii::app()->clientScript->registerCssFile($baseUrl.'/bootstrap-wysihtml5.css');
		} else {
			throw new Exception('EClEditor - Error: Couldn\'t find assets to publish.');
		}
	}
}
