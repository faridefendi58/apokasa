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
		<h4 class="panel-title"><i class="fa fa-briefcase"></i> <?php echo Yii::t('order','Create Initial Capital');?></h4>
	</div>
	<div class="panel-body">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'equity-form',
			'enableAjaxValidation'=>false,
		)); ?>

			<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>
			
				<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<?php echo $form->labelEx($model,'equity'); ?>
						<?php echo $form->textField($model,'equity',array('size'=>60,'maxlength'=>128)); ?>
						<?php echo $form->error($model,'equity'); ?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="clearfix"></div>
				<div class="col-md-12">
					<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
				</div>
			</div>

		<?php $this->endWidget(); ?>
	</div>
</div>
<?php $this->widget('application.extensions.moneymask.MMask');?>
<script type="text/javascript">
$(function(){
	$('input[id=\"PaymentSession_equity\"]').maskMoney({symbol:'', showSymbol:false, thousands:'.', decimal:',', symbolStay: true,precision:0});
});
</script>
