<?php
Yii::import('zii.widgets.CPortlet');

class relatedPost extends CPortlet{
	public $visible=true;
	public $title='Related Post';
	public $postid;
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
		$post=Post::model()->findByPk($this->postid);
		$criteria=new CDbCriteria;
		$criteria->compare('post_type',0);
		$criteria->limit=5;
		$dataProvider=new CActiveDataProvider('Post',array('criteria'=>$criteria,'pagination'=>false));
		/*$sql="(SELECT t.id, t.title FROM `app_post` `t` WHERE t.member_id = ".$post->member_id.") UNION (SELECT t.id, t.title FROM `app_post` `t` WHERE t.author_id = 0)";
		
		$dataProvider=new CSqlDataProvider($sql, array(
			'totalItemCount'=>count(Yii::app()->db->createCommand($sql)->queryColumn()),
		    'sort'=>array(
				'attributes'=>array(
 					'dateentry',
				),
			),
		    'pagination'=>array(
			'pageSize'=>5,
		    ),
		));*/
		$this->render('_related_post',array('dataProvider'=>$dataProvider));
	}
}

?>
