<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class DController extends CController
{
	public $layout='/layouts/main';
	public $menu=array();
	public $breadcrumbs=array();
	public $meta_description;
	public $meta_keywords;

	public function beforeAction($action)
	{
		if(parent::beforeAction($action)) {
			$set_counter=PcounterUsers::setCounter();
			if(isset($_GET['lang'])){
				if(Yii::app()->user->getState('language')->code!=$_GET['lang']){
					$criteria=new CDbCriteria;
					if(!empty($_GET['lang']))
						$criteria->compare('code',strtolower($_GET['lang']));
					else
						$criteria->compare('code','id');
					$language=PostLanguage::model()->find($criteria);
					if(!empty($language))
						Yii::app()->user->setState('language',$language);
				}
			}
			if(!Yii::app()->user->hasState('language')){
				$criteria=new CDbCriteria;
				$criteria->compare('code','id');
				$language=PostLanguage::model()->find($criteria);
				if(!empty($language))
					Yii::app()->user->setState('language',$language);
			}
			if(Yii::app()->user->getState('language')->code!=Yii::app()->language)
				Yii::app()->setLanguage(Yii::app()->user->getState('language')->code);
			$this->meta_description=Yii::app()->config->get('meta_description');
			$this->meta_keywords=Yii::app()->config->get('meta_keywords');
			return true;
		}else
			return false;
	}
	
	/**
	 * clean string from non alphanumeric
	 */
	public function alphaNumeric($string,$replace='+')
	{
		//return preg_replace("/[\/\&%#\$]/",$replace,$string);
		//remove all non alphanumeric
		return preg_replace("/[^[:alnum:][:space:]]/ui", $replace, $string);
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
	public function cleanString($string,$replace='+')
	{
		return preg_replace("/[\/\&%#*\$']/",$replace,$string);
	}

	public function createTag($string)
	{
		$string=$this->cleanString(strtolower($string),"");
		$pecah=array();
		if(!empty($string)){
			$pecah=explode(" ",$string);
		}
		$tag=serialize($pecah);
		return $tag;
	}

	public function getCustomBreadcrumb($links=array())
	{
	    	if(empty($links))
			$links=$this->breadcrumbs;

	   	echo '<ul class="breadcrumb">';
	    	echo '<li><i class="icon-home"></i>'.CHtml::link(Yii::t('zii','Home'),Yii::app()->homeUrl).'<span class="divider"><i class="icon-angle-right"></i></span></li>';
	    	foreach($links as $label=>$url)
	    	{
			if(is_string($label) || is_array($url))
		    		echo '<li>'.CHtml::link(CHtml::encode($label), $url);
			else
		    		echo '<li>'.CHtml::encode($url);
			echo '<span class="divider"><i class="icon-angle-right"></i></span>';
			echo '</li>';
	    	}
		echo '</ul>';
	}

	public function getParsePageTitle(){
		if(!empty($this->pageTitle)){
			$pecah=explode(" - ",$this->pageTitle);
			return $pecah[1];
		}
	}

	public function sendMail($mail_from,$mail_to,$subject=null,$variable=array(),$template){
		$template_email =  Yii::app()->file->set('emails/'.$template.'.html', true);
		$user_email = $template_email->contents;
			
		$user_email = FPMail::addHF($user_email);
		$pecah=explode("@",$mail_to);
		if(is_array($pecah))
			$nama=$pecah[0];
		$vari = array(
					'{-tanggal-}'=>date(Yii::app()->params['emailTime']),
					'{-sitename-}'=>Yii::app()->config->get('site_name'),
					'{-logo-}'=>Yii::app()->request->hostInfo.Yii::app()->request->baseUrl.'/'.Yii::app()->config->get('logo'),
					'{-support-}'=>Yii::app()->config->get('admin_email'),
					'{-copyright-}'=>'Copyright &copy; '.date(Y).' '.Yii::app()->config->get('site_name').'. All rights reserved.'
					//'{-url-}'=>CHtml::link(Yii::app()->createAbsoluteUrl('/timeline/tProject/confirm/pivd/'.$model->id),Yii::app()->createAbsoluteUrl('/timeline/tProject/confirm/pivd/'.$model->id)),
		);	
		$vari = $vari+$variable;

		// just send to user	
		$user_email = str_replace(array_keys($vari), array_values($vari), $user_email);
		$email = Yii::app()->email;
        	$email->to = $mail_to;
		$email->from = $mail_from; //Yii::app()->config->get('admin_email');
        	$email->subject = $subject;
        	$email->message = $user_email;
        	$email->send();
	}
}
