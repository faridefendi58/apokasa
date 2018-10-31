<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rbac-group-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group col-md-8">
		<?php echo $form->labelEx($model,'group_name'); ?>
		<?php echo $form->textField($model,'group_name',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'group_name'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'level'); ?>
		<?php echo $form->dropDownList($model,'level',RbacGroup::listLevel()); ?>
		<?php echo $form->error($model,'level'); ?>
	</div>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Save') : Yii::t('global','Update'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
