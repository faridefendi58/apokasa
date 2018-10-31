<?php
Yii::import('zii.widgets.CPortlet');

class ProductWidget extends CPortlet{
	public $visible=true;
	public $product_type=4;
	
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
		//$criteria->compare('product_rel.type',$this->product_type);
		//$criteria->with=array('product_rel');

		$dataProvider=new CActiveDataProvider('ProductItems',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>20)));
		$this->render('_product',array('dataProvider'=>$dataProvider));
	}
}

?>
