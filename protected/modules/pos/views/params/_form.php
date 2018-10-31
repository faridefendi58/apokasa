<?php
Yii::app()->clientScript->registerScript('upload', "
$('#image-upload').click(function(){
	$('#image-form').toggle();
	$('#value-form').toggle();
	return false;
});
");
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'params-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype' =>'multipart/form-data'),
)); ?>

	<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'params_name'); ?>
		<?php if($model->isNewRecord):?>
		<?php echo $form->textField($model,'params_name',array('size'=>30,'maxlength'=>128)); ?>
		<?php else:?>
		<?php echo $form->textField($model,'params_name',array('size'=>30,'maxlength'=>128,'readOnly'=>true)); ?>
		<?php endif;?>
		<?php echo $form->error($model,'params_name'); ?>
	</div>

	<div class="form-group col-md-8" id="value-form">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>
	
	<?php if($model->isNewRecord):?>
	<div class="form-group">
		<label>&nbsp;</label>
		<?php echo CHtml::link('Image Upload','#',array('id'=>'image-upload')); ?>
	</div>
	<div class="form-group" id="image-form" style="display:none">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->fileField($model,'image',array('size'=>30)); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>
	<?php else:?>
		<?php if($model->type==3):?>
		<div class="form-group">
			<?php echo $form->labelEx($model,'image'); ?>
			<?php echo $form->fileField($model,'image',array('size'=>30)); ?>
			<?php echo $form->error($model,'image'); ?>
		</div>
		<?php endif;?>
	<?php endif;?>

	<div class="form-group col-md-12">
		<?php echo $form->labelEx($model,'notes'); ?>
		<?php echo $form->textArea($model,'notes',array('rows'=>3)); ?>
		<?php echo $form->error($model,'notes'); ?>
	</div>

	<div class="form-group col-md-12">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>
