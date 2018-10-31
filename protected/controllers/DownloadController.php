<?php

class DownloadController extends DController
{
	public $layout='column_download';

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
				'actions'=>array('catalogue','thankyou','file'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCatalogue($slug)
	{
		$model=new Download;
		if(isset($_POST['Download'])){
			$model->attributes=$_POST['Download'];
			$model->catalogue_id=Catalogue::model()->findByAttributes(array('key'=>$slug))->id;
			$model->date_entry=date(c);
			if($model->save()){
				Yii::app()->user->setState('terimakasih','<h2>Thanks! Your request was sent successfully.</h2><p>Please check our email for the download link!');
				$this->redirect(array('/thank-you'));
			}
		}
		$this->render('catalogue',array('model'=>$model));
	}

	public function actionThankyou()
	{
		$this->layout='column_thanks';
		if(Yii::app()->user->hasState('terimakasih')){
			$pesan = Yii::app()->user->getState('terimakasih');
			//Yii::app()->user->setState('terimakasih',null);
			$this->render('terimakasih');
		}else
			$this->redirect(array('/site/home'));
	}

	public function actionFile($catalogue)
	{
		$model=Catalogue::getCatalogue($_GET['token']);
		if(empty($model->id)){
			header("HTTP/1.0 404 Not Found");
			exit();
		}
		$src = "uploads/catalogues/".$catalogue;
		if(@file_exists($src)) {
			$path_parts = @pathinfo($src);
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
                        //header('Content-Disposition: attachment; filename='.basename($src));
			header('Content-Disposition: attachment; filename="'.basename($src).'"');
			header('Content-Transfer-Encoding: binary');
 			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($src));
			ob_clean();
			flush();
			readfile($src);
		} else {
			header("HTTP/1.0 404 Not Found");
			exit();
		}
	}
}
