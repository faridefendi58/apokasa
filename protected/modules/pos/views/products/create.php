<?php
$this->breadcrumbs=array(
	Yii::t('product','Product')=>array('view'),
	Yii::t('global','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('product','Product'), 'url'=>array('view')),
	array('label'=>Yii::t('global','List').' '.Yii::t('product','Product Type'), 'url'=>array('productTypes/view')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Create');?> <?php echo Yii::t('order','Product');?></h4>
	</div>
	<div class="panel-body">
		<?php if(Yii::app()->user->hasFlash('create')): ?>
			<div class="alert alert-success mb10">
				<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
				<?php echo Yii::app()->user->getFlash('create'); ?>
			</div>
		<?php endif; ?>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'model2'=>$model2, 'model3' => $model3)); ?>
	</div>
</div>
