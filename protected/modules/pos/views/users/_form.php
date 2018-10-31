<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>30,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<?php if($model->isNewRecord): ?>
	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	<?php endif;?>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'group_id'); ?>
		<span id="list-group"><?php echo $form->dropDownList($model,'group_id',RbacGroup::items()); ?></span>
		<?php echo $form->error($model,'group_id'); ?>
	</div>

	<div class="form-group col-md-12">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Save') : Yii::t('global','Update'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>
