<?php
$this->breadcrumbs=array(
	'Promo'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('global','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Promo', 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' Promo', 'url'=>array('create')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Update');?> Promo <?php echo $model->id; ?></h4>
	</div>
	<div class="panel-body">
		<?php if(Yii::app()->user->hasFlash('update')): ?>
			<div class="alert alert-success">
				<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
				<?php echo Yii::app()->user->getFlash('update'); ?>
			</div>
		<?php endif; ?>
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>
