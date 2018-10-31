<?php
$this->breadcrumbs=array(
	'Rbac Groups'=>array('view'),
	'Update'=>array('view','id'=>$model->id),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Group', 'url'=>array('view'),'visible'=>Rbac::ruleAccess('read_p')),
	array('label'=>'Create Group', 'url'=>array('create'),'visible'=>Rbac::ruleAccess('create_p')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Update').' '.Yii::t('global','Group');?> <?php echo $model->id; ?></h4>
	</div>
	<div class="panel-body">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>
