<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableAjaxValidation'=>true,
		'htmlOptions'=>array('class'=>'form-inline','role'=>'form'),
)); ?>
	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'username',array('class'=>'sr-only')); ?>
		<div class="input-group">
			<span class="input-group-addon">
				<span class="elusive icon-envelope"></span>
			</span>
			<?php echo $form->textField($model,'username',array('class'=>'form-control','placeholder'=>'Enter email')); ?>
			<?php //echo $form->error($model,'username'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'password',array('class'=>'sr-only')); ?>
		<div class="input-group">
			<span class="input-group-addon">
				<span class="elusive icon-key"></span>
			</span>
			<?php echo $form->passwordField($model,'password',array('class'=>'form-control','placeholder'=>'Enter Password')); ?>
			<?php //echo $form->error($model,'password'); ?>
		</div>
	</div>
	<?php echo CHtml::submitButton('sign in',array('class'=>'btn btn-default')); ?>
<?php $this->endWidget(); ?>
<p><?php echo Yii::t('menu',"Don't have account ?, please");?> <?php echo CHtml::link('signup',array('/member/signup'));?> <?php echo Yii::t('menu','here !');?></p>	
