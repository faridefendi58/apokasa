<?php

class PostController extends DController
{
	public $layout='//layouts/column1';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated users to access all actions
				'actions'=>array('commentApproval'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$this->layout='column_inside';
		$post=$this->loadModel();
		$comment=$this->newComment($post);
		if(!Yii::app()->user->hasState('post_'.$post->id)){
			$post->content_rel->viewed=$post->content_rel->viewed+1;
			if($post->update())
				Yii::app()->user->setState('post_'.$post->id,true);
		}
		
		$this->render('view',array(
			'model'=>$post,
			'comment'=>$comment,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->layout='column1';
		
		$criteria=new CDbCriteria;
		$criteria->compare('status',Post::STATUS_PUBLISHED);
		$criteria->compare('post_type',0);
		if(!empty($_GET['type'])){
			$idcategory=PostCategory::getIdByKey($_GET['type']);
			if(is_array($idcategory)){
				$criteria->compare('post_category',$idcategory[0]);
				foreach($idcategory as $idc=>$dtc){
					if($idc>0)
						$criteria->compare('post_category',$dtc,false,'OR');
				}
			}else{
				$criteria->compare('post_category',$idcategory);
			}
		}
		$criteria->order='update_time DESC';
		$criteria->with=array('commentCount');
		
		if(isset($_GET['tag']))
			$criteria->addSearchCondition('tags',$_GET['tag']);

		$dataProvider=new CActiveDataProvider('Post', array(
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['postsPerPage'],
			),
			'criteria'=>$criteria,
		));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Suggests tags based on the current user input.
	 * This is called via AJAX when the user is entering the tags input.
	 */
	public function actionSuggestTags()
	{
		if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
		{
			$tags=Tag::model()->suggestTags($keyword);
			if($tags!==array())
				echo implode("\n",$tags);
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
			{
				if(Yii::app()->user->isGuest)
					$condition='status='.Post::STATUS_PUBLISHED.' OR status='.Post::STATUS_ARCHIVED;
				else
					$condition='';
				$this->_model=Post::model()->findByPk($_GET['id'], $condition);
			}
			if(isset($_GET['slug']))
			{
				$criteria=new CDbCriteria;
				$criteria->compare('t.status',Post::STATUS_PUBLISHED);
				$criteria->compare('t.status',Post::STATUS_ARCHIVED,false,'OR');
				$criteria->compare('LOWER(content_rel.slug)',strtolower($_GET['page_slug']));
				$criteria->with=array('content_rel');
				$criteria->together=true;
			}
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Creates a new comment.
	 * This method attempts to create a new comment based on the user input.
	 * If the comment is successfully created, the browser will be redirected
	 * to show the created comment.
	 * @param Post the post that the new comment belongs to
	 * @return Comment the comment instance
	 */
	protected function newComment($post)
	{
		$comment=new Comment;
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($comment);
			Yii::app()->end();
		}
		if(isset($_POST['Comment']))
		{
			$comment->attributes=$_POST['Comment'];
			if($post->addComment($comment))
			{
				if($comment->status==Comment::STATUS_PENDING)
					Yii::app()->user->setFlash('commentSubmitted','Thank you for your comment. Your comment will be posted once it is approved.');
				$this->refresh();
			}
		}
		return $comment;
	}
}
