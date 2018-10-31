<?php
Yii::import('zii.widgets.CPortlet');

class ContactWidget extends CPortlet{
	public $visible=true;
	public $position='left'; //left, bottom
	
	public function init()
	{
		if($this->visible)
		{
		}
	}
 
	public function run()
	{
		if($this->visible)
		{
	 		$this->renderContent();
		}
	}
	
	protected function renderContent()
	{
		if($this->position=='left')
			$model=new ContactForm('create');
		else
			$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$data=array(
						'template'=>'email_contact_admin',
						'subject'=>'[Java Connection] Kontak User',
						'mail_from'=>Yii::app()->config->get('admin_email'),
						'from_name'=>Yii::app()->config->get('site_name'),
						'mail_to'=>Yii::app()->config->get('admin_email'),
						'to_name'=>Yii::app()->config->get('site_name'),
						'variable'=>array(
								'{-name-}'=>$model->name,
								'{-email-}'=>$model->email,
								'{-subject-}'=>$model->subject,
								'{-body-}'=>$model->body,
								'{-company-}'=>$model->company,
								'{-country-}'=>$model->country,
							)
					);
				$this->sendMail($data);
				Yii::app()->user->setFlash('contact',Yii::t('global','Thank you for contacting us. We will respond to you as soon as possible.'));
				$this->controller->refresh();
			}
		}

		$this->render('_contact',array('model'=>$model));
	}

	private function sendMail($data){
		$template_email =  Yii::app()->file->set('emails/'.$data['template'].'.html', true);
		$user_email = $template_email->contents;
			
		$user_email = FPMail::addHF($user_email);
		$vari = array(
					'{-tanggal-}'=>date(Yii::app()->params['emailTime']),
					'{-sitename-}'=>Yii::app()->config->get('site_name'),
					'{-logo-}'=>Yii::app()->request->hostInfo.Yii::app()->request->baseUrl.'/uploads/images/'.Yii::app()->config->get('logo'),
					'{-support-}'=>Yii::app()->config->get('admin_email'),
					'{-copyright-}'=>'Copyright &copy; '.date(Y).' '.Yii::app()->config->get('site_name').'. All rights reserved.'
		);	
		$vari = $vari+$data['variable'];
		// just send to user	
		$user_email = str_replace(array_keys($vari), array_values($vari), $user_email);
		
		$email = Yii::app()->bbmail;
		$email->setSubject($data['subject']);
		$email->setBodyHtml($user_email);
		$email->setFrom($data['mail_from'], $data['from_name']);
		$email->addTo($data['mail_to'], $data['to_name']);
        
		$email->send('sendmail', array('mailer'=>'sendmail'));
		return true;
	}
}

?>
