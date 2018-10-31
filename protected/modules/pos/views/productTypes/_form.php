<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-type-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'name',array('class'=>'control-label')); ?>
		<?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
	</div>

	<div class="form-group col-md-8">
		<?php echo $form->labelEx($model,'description',array('class'=>'control-label')); ?>
		<?php echo $form->textField($model,'description',array('class'=>'form-control')); ?>
	</div>

	<div class="form-group col-md-12">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>
