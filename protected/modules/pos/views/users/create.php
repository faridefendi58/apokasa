<?php
$this->breadcrumbs=array(
	'Users'=>array('view'),
	Yii::t('global','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' User', 'url'=>array('view'),'visible'=>Rbac::ruleAccess('read_p')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Create');?> User</h4>
	</div>
	<div class="panel-body">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>
