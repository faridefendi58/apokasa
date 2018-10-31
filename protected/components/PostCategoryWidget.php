<?php
Yii::import('zii.widgets.CPortlet');

class PostCategoryWidget extends CPortlet{
	public $visible=true;
	public $title='Post Category';
	public $limit=5;
	public $type='right'; //right, bottom
	public $itemsCssClass='sidebar-list';
	
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
		$criteria->limit=$this->limit;

		$dataProvider=new CActiveDataProvider('PostCategory',array('criteria'=>$criteria,'pagination'=>false));
		
		$this->render('_post_category',array('dataProvider'=>$dataProvider));
	}
}

?>
