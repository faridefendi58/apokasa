<?php
Yii::import('zii.widgets.CPortlet');

class bottomMenu extends CPortlet{
	public $visible=true;
	
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
		$items=Menu::items('bottom_menu');
		
		$this->render('_bottommenu',array('items'=>$items));
	}
}

?>
