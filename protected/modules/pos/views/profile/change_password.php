<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	'Profile'=>array('update'),
	Yii::t('global','Change').' Password',
);

$this->menu=array(
	array('label'=>Yii::t('menu','Account Setting'), 'url'=>array('update')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Change');?> Password</h4>
	</div>
	<div class="panel-body">
		<?php if(Yii::app()->user->hasFlash('changepass')): ?>
		<div class="alert alert-success">
			<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
			<?php echo Yii::app()->user->getFlash('changepass'); ?>
		</div>
		<?php endif; ?>

		<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'changepass-form',
				'enableAjaxValidation'=>false,
		)); ?>

		<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>
			
		<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<?php echo $form->labelEx($model,'passwordlm'); ?>
					<?php echo $form->passwordField($model,'passwordlm'); ?>
					<?php echo $form->error($model,'passwordlm'); ?>
				</div>

				<div class="form-group">
					<?php echo $form->labelEx($model,'passwordbr'); ?>
					<?php echo $form->passwordField($model,'passwordbr'); ?>
					<?php echo $form->error($model,'passwordbr'); ?>
				</div>

				<div class="form-group">
					<?php echo $form->labelEx($model,'passwordbr_repeat'); ?>
					<?php echo $form->passwordField($model,'passwordbr_repeat'); ?>
					<?php echo $form->error($model,'passwordbr_repeat'); ?>
				</div>
			</div>
			<div class="col-md-6">
				<?php if(CCaptcha::checkRequirements()): ?>
				<div class="form-group">
					<?php echo $form->labelEx($model,'verifyCode'); ?>
					<?php $this->widget('CCaptcha',array('clickableImage'=>true,'buttonLabel'=>'','imageOptions'=>array('class'=>'captcha'))); ?>
					<p><?php echo Yii::t('global','Please enter the letters as they are shown in the image above.<br/>Letters are not case-sensitive.');?></p>
				</div>
				<div class="form-group">
					<label>&nbsp;</label>
					<?php echo $form->textField($model,'verifyCode',array('placeholder'=>'Masukkan Kode Verifikasi')); ?>
				</div>
				<?php endif; ?>
				<div class="form-group">
					<?php echo CHtml::submitButton(Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
				</div>
			</div>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
