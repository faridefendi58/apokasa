<?php if(Yii::app()->theme->name=='classic'):?>
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'contact-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('class'=>'form-horizontal','role'=>'form'),
	)); ?>
	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<?php if(Yii::app()->user->hasFlash('contact')): ?>
	<div class="alert alert-success">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<?php echo Yii::app()->user->getFlash('contact'); ?>
	</div>
	<?php endif; ?>
	<div class="col-sm-6">
		<div class="clearfix padding10">
		<div class="form-group">
			<?php echo $form->textField($model,'name',array('class'=>'form-control','placeholder'=>'Name')); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
		<div class="form-group">
			<?php echo $form->textField($model,'email',array('class'=>'form-control','placeholder'=>'Telepon')); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>
		<div class="form-group">
			<?php echo $form->textField($model,'phone',array('class'=>'form-control','placeholder'=>'Name')); ?>
			<?php echo $form->error($model,'phone'); ?>
		</div>
		<div class="form-group">
			<?php echo $form->textField($model,'company',array('class'=>'form-control','placeholder'=>'Company')); ?>
			<?php echo $form->error($model,'company'); ?>
		</div>
		<div class="form-group">
			<?php echo $form->textField($model,'country',array('class'=>'form-control','placeholder'=>'Country')); ?>
			<?php echo $form->error($model,'country'); ?>
		</div>
		<?php echo $form->hiddenField($model,'subject',array('value'=>'Kontak User')); ?>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="clearfix padding10">
			<div class="form-group">
				<?php echo $form->textArea($model,'body',array('class'=>'form-control','placeholder'=>'Message','rows'=>4)); ?>
				<?php echo $form->error($model,'body'); ?>
			</div>
			<?php if(extension_loaded('gd')): ?>
			<div class="form-group row">
				<div class="col-sm-5">
				<?php $this->widget('CCaptcha',array('clickableImage'=>true,'showRefreshButton'=>false)); ?>
				</div>
				<div class="col-sm-7 no-padding">
					<?php echo $form->textField($model,'verifyCode',array('class'=>'form-control','placeholder'=>'Verification code')); ?>
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<?php echo CHtml::submitButton(Yii::t('global','Submit'),array('class'=>'btn btn-green-square btn-block','style'=>'min-width:100px;')); ?>
			</div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
<?php elseif (Yii::app()->theme->name=='jagungbakar'):?>
<div class="comment-form" data-animated="0">
	<div class="content-head">
		<h3>Contact Us</h3>
		<p><span>send us a message</span></p>
	</div>
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'contactForm',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('class'=>'form-horizontal','role'=>'form'),
	)); ?>
	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<?php if(Yii::app()->user->hasFlash('contact')): ?>
	<div class="alert alert-success">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<?php echo Yii::app()->user->getFlash('contact'); ?>
	</div>
	<?php endif; ?>
	<div class="col-sm-6">
		<span><i class="fa fa-user"></i></span>
		<?php echo $form->textField($model,'name',array('placeholder'=>'Name')); ?>
		<span><i class="fa fa-envelope-o"></i></span>
		<?php echo $form->textField($model,'email',array('placeholder'=>'Email')); ?>
		<span><i class="fa fa-phone"></i></span>
		<?php echo $form->textField($model,'phone',array('placeholder'=>'Phone')); ?>
		<span><i class="fa fa-home"></i></span>
		<?php echo $form->textField($model,'company',array('placeholder'=>'Company')); ?>
		<?php echo $form->hiddenField($model,'subject',array('value'=>'Kontak User')); ?>
	</div>
	<div class="col-sm-6">
		<div class="clearfix padding10">
			<?php echo $form->textArea($model,'body',array('placeholder'=>'Message','rows'=>4)); ?>
			<?php if(extension_loaded('gd')): ?>
			<div class="row">
				<div class="col-sm-4">
				<?php $this->widget('CCaptcha',array('clickableImage'=>true,'showRefreshButton'=>false)); ?>
				</div>
				<div class="col-sm-8 no-padding">
					<span><i class="fa fa-lock"></i></span>
					<?php echo $form->textField($model,'verifyCode',array('placeholder'=>'Verification code')); ?>
				</div>
			</div>
			<?php endif; ?>
			<span><i class="fa fa-floppy-o"></i></span>
			<?php echo CHtml::submitButton(Yii::t('global','Submit'),array('class'=>'btn btn-block','style'=>'min-width:100px;')); ?>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>
<?php endif;?>
