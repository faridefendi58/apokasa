<?php
Yii::import('zii.widgets.CPortlet');

class SearchWidget extends CPortlet{
	public $visible=true;
	public $class_name;
	public $placeholder;
	public $destination='#content-frame';
	
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
		
		$this->render('_search');
	}
}

?>
