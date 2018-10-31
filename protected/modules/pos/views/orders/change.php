<?php
$this->breadcrumbs=array(
	Yii::t('order','Orders')=>array('view'),
	Yii::t('global','Create'),
);

$this->menu=array(
	array(
		'label'=>Yii::t('global','List').' '.Yii::t('order','Orders'), 
		'url'=>array('view'),
		'visible'=>RbacUserAccess::isChecked(Yii::app()->controller->module->id,'orders',Yii::app()->user->id,'read_p')
	),
	array(
		'label'=>Yii::t('global','List').' '.Yii::t('order','Invoices'), 
		'url'=>array('invoices/view'),
		'visible'=>RbacUserAccess::isChecked(Yii::app()->controller->module->id,'invoices',Yii::app()->user->id,'read_p')
	),
	array(
		'label'=>Yii::t('global','List').' '.Yii::t('order','Products'), 
		'url'=>array('products/view'),
		'visible'=>RbacUserAccess::isChecked(Yii::app()->controller->module->id,'products',Yii::app()->user->id,'read_p')
	),
	array(
		'label'=>Yii::t('global','List').' '.Yii::t('order','Promotions'), 
		'url'=>array('promotions/view'),
		'visible'=>RbacUserAccess::isChecked(Yii::app()->controller->module->id,'promotions',Yii::app()->user->id,'read_p')
	),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><i class="fa fa-briefcase"></i> <?php echo Yii::t('order','Create Sales');?></h4>
	</div>
	<div class="panel-body">
		<?php echo $this->renderPartial('_form', array('model'=>$model,'promocode'=>$promocode)); ?>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$('.page-container').toggleClass('hidden-sidebar');
	$('.sidebar').hide();
});
</script>
