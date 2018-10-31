<?php

class ChangePasswordForm extends CFormModel
{
	public $passwordlm;
	public $passwordbr;
	public $passwordbr_repeat;
	public $verifyCode;

	public function rules()
	{
		return array(
			array('passwordlm', 'required'),
			array('passwordlm', 'cekpass'),
			array('passwordbr', 'required'),
			array('passwordbr', 'length', 'min'=>3),
			array('passwordbr', 'length', 'max'=>255),
			array('passwordbr_repeat', 'required'),
        		array('passwordbr_repeat', 'comparePass'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(),'on'=>'change'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'passwordlm'=>Yii::t('member','Old Password'),
			'passwordbr'=>Yii::t('member','New Password'),
			'passwordbr_repeat'=>Yii::t('member','Confirm New Password'),
			'verifyCode'=>Yii::t('member','Verification Code'),
		);
	}
	
	public function cekpass($attribute,$params)
	{
		if(md5($this->passwordlm)!==Member::model()->findByPk(Yii::app()->user->id)->password)
			$this->addError('passwordlm','Password Lama tidak benar.');
		
		return;
	}
	
	public function comparePass($attribute,$params)
	{
		if($this->passwordbr_repeat!==$this->passwordbr)
			$this->addError('passwordbr_repeat','Password harus diulang secara tepat.');
		
		return;
	}
}
