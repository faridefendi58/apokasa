<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>
			
		<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<?php echo $form->labelEx($model,'username'); ?>
				<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>128)); ?>
				<?php echo $form->error($model,'username'); ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<?php echo $form->labelEx($model,'status'); ?>
				<?php echo $form->dropDownList($model,'status',Lookup::items('UserStatus')); ?>
				<?php echo $form->error($model,'status'); ?>
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
