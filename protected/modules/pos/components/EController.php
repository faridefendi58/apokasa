<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class EController extends CController
{
	public $layout='/layouts/main';
	public $menu=array();
	public $breadcrumbs=array();

	public function behaviors()
	{
		return array(
		    'eexcelview'=>array(
		        'class'=>'ext.eexcelview.EExcelBehavior',
		    ),
		);
	}

	public function beforeAction($action)
	{
	  if (parent::beforeAction($action)) {
		Yii::app()->config->_setDb('db2');
		return true;
	  } else
		return false;
	}
	
	/**
	 * clean string from non alphanumeric
	 */
	public function alphaNumeric($string,$replace='+')
	{
		return preg_replace("/[\/\&%#\$]/",$replace,$string);
	}

	/**
	 * unformat money format to base number
	 */
	public function money_unformat($number,$thousand='.',$decimal=',')
	{
		if(strstr($number, $thousand))
			$number = str_replace($thousand, '', $number);
		if(strstr($number, $decimal))
			$number = str_replace($decimal, '.', $number);
		return $number; 
	}

	/**
	 * clean string
	 */
	public function cleanNumber($string,$replace='+')
	{
		return preg_replace("/[\/\&%#* \$]/",$replace,$string);
	}

	/**
	 * clean string
	 */
	public function cleanString($string,$replace='+')
	{
		return preg_replace("/[\/\&%#*\$']/",$replace,$string);
	}

	/**
	 * format money format to base number
	 */
	public function money_format($number,$thousand='.',$decimal=',')
	{
		if(Yii::app()->user->hasState('currency')){
			$currency_id=Yii::app()->user->getState('currency');
			$model=Currency::item($currency_id);
		}else{
			$model=Currency::getDefaultModel();
		}
		$number=number_format($number,$model->decimal_digit,$model->decimal_separator,$model->thousand_separator);
		return $number; 
	}
}
