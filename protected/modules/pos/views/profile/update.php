<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	'Profile'=>array('update'),
	Yii::t('global','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('menu','Change Password'), 'url'=>array('changePassword')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Update');?> Profil</h4>
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
