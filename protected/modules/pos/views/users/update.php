<?php
$this->breadcrumbs=array(
	'Users'=>array('view'),
	'Update'=>array('update','id'=>$model->id),
	$model->id,
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('view'),'visible'=>Rbac::ruleAccess('read_p')),
	array('label'=>'Create User', 'url'=>array('create'),'visible'=>Rbac::ruleAccess('create_p')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Update');?> User</h4>
	</div>
	<div class="panel-body">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>
