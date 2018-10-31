<?php
class FPMail{
	public function addHeaderFooter($msg, $head='default_head.html', $foot='default_foot.html', $temp='default_mail.html'){
		$fl =  Yii::app()->file->set('emails/'.$head, true);
		$hd = $fl->contents;
		$fl =  Yii::app()->file->set('emails/'.$foot, true);
		$fot = $fl->contents;
		$fl =  Yii::app()->file->set('emails/default_copyright.html', true);
		$cpr = $fl->contents;
		$fl =  Yii::app()->file->set('emails/'.$temp, true);
		$template = $fl->contents;
		
		return str_replace(array('{-head-}','{-content-}','{-foot-}','{-copyright-}'),array($hd, $msg, $fot, $cpr), $template);
	}
	
	public function addHF($msg, $head='default_head.html', $foot='default_foot.html', $temp='default_mail.html'){
		return self::addHeaderFooter($msg,$head,$foot,$temp);
	}
	
	public function useTemplate($msg, $temp='default_mail_wt.html'){
		$fl =  Yii::app()->file->set('emails/'.$temp, true);
		$template = $fl->contents;
		return str_replace('{-content-}',$msg, $template);
	}
}
?>