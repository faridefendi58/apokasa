<?php
Yii::import('zii.widgets.CPortlet');

class mainMenu extends CPortlet{
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
		$linkOptions=array();
		$items=Menu::items('main_menu');
		
		$this->render('_mainmenu',array('items'=>$items));
	}
}

?>
