<?php
Yii::import('zii.widgets.CPortlet');

class GalleryWidget extends CPortlet{
	public $visible=true;
	public $limit=6;
	
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
		$criteria=new CDbCriteria;
		$criteria->order='date_entry DESC';
		$dataProvider=new CActiveDataProvider('Gallery',array('criteria'=>$criteria));

		$this->render('_gallery',array('dataProvider'=>$dataProvider));
	}
}

?>
