<?php
Yii::import('zii.widgets.CPortlet');

class PostWidget extends CPortlet{
	public $visible=true;
	public $limit=5;
	public $type='latest'; //latest, popular, carousel
	public $id;
	public $themes;
	
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
	 		$this->themes=Yii::app()->theme->name;
		    $this->renderContent();
		}
	}
	
	protected function renderContent()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('post_type',0);
		if($this->type=='popular')
			$criteria->order='viewed DESC';
		else
			$criteria->order='create_time DESC';
		$criteria->limit=$this->limit;

		$dataProvider=new CActiveDataProvider('Post',array('criteria'=>$criteria,'pagination'=>false));

		$this->render('_post',array('dataProvider'=>$dataProvider));
	}
}

?>
