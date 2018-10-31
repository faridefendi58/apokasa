<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class FController extends CController
{
	public $layout='/layouts/main';
	public $menu=array();
	public $breadcrumbs=array();

	public function beforeAction($action)
	{
	  if (parent::beforeAction($action)) {
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
}
