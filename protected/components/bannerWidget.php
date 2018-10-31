<?php
Yii::import('zii.widgets.CPortlet');

class bannerWidget extends CPortlet{
	public $visible=true;
	public $key;
	public $htmlOptions=array();
	
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
		$htmlOptions='';
		if(count($this->htmlOptions)>0){
			foreach($this->htmlOptions as $index=>$data){
				$htmlOptions.=$index.'='.$data.' ';
			}
		}
		$img='<img src="'.Yii::app()->createUrl('/site/image',array('key'=>$this->key)).'" '.$htmlOptions.'>';
		$criteria=new CDbCriteria;
		$criteria->compare('t.key',$this->key);
		$model=Banner::model()->find($criteria);
		if(!empty($model->url)){
			preg_match('/^http?([^:]+)/', $model->url, $matches);	
			if(count($matches)>0)
				echo '<a href="'.$model->url.'" title="'.$model->description.'">'.$img.'</a>';
			else{
				if($model->url=='#')
					echo '<a href="#" title="'.$model->description.'">'.$img.'</a>';
				else
					echo '<a href="http://'.$model->url.'" title="'.$model->description.'">'.$img.'</a>';
			}
		}else
			echo $img;
	}
}

?>
