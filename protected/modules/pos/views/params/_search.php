<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="form-group col-md-4">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->label($model,'params_name'); ?>
		<?php echo $form->textField($model,'params_name',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->label($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="form-group col-md-12">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>
