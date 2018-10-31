<?php
$this->breadcrumbs=array(
	'Params'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('global','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Params', 'url'=>array('index')),
	array('label'=>Yii::t('global','Create').' Params', 'url'=>array('create')),
	array('label'=>Yii::t('global','View').' Params', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Update');?> Params <?php echo $model->id; ?></h4>
	</div>
	<div class="panel-body">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>
