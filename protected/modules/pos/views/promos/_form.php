<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'promo-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'code',array('class'=>'control-label')); ?>
		<?php echo $form->textField($model,'code',array('class'=>'form-control')); ?>
	</div>

	<div class="form-group col-md-8">
		<?php echo $form->labelEx($model,'description',array('class'=>'control-label')); ?>
		<?php echo $form->textField($model,'description',array('class'=>'form-control')); ?>
	</div>

	<div class="form-group col-md-3">
		<?php echo $form->labelEx($model,'type',array('class'=>'control-label')); ?>
		<?php echo $form->dropDownList($model,'type',Promo::items(),array('class'=>'form-control')); ?>
	</div>

	<div class="form-group col-md-3">
		<?php echo $form->labelEx($model,'value',array('class'=>'control-label')); ?>
		<?php echo $form->textField($model,'value',array('class'=>'form-control')); ?>
	</div>

	<div class="form-group col-md-6">
		<label class="control-label col-sm-12">Period</label>
		<div class="col-md-6 col-sm-6">
			<?php
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$model,
					'attribute'=>'start_at', //attribute name
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'yy-mm-dd',
						'changeMonth' => 'true',
						'changeYear'=>'true',
						'constrainInput' => 'false'
					),
					'htmlOptions'=>array(
						'class'=>'form-control',
						'placeholder'=>'Start From',
					),
				));
			?>
		</div>
		<div class="col-md-6 col-sm-6">
			<?php
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$model,
					'attribute'=>'end_at', //attribute name
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'yy-mm-dd',
						'changeMonth' => 'true',
						'changeYear'=>'true',
						'constrainInput' => 'false'
					),
					'htmlOptions'=>array(
						'class'=>'form-control',
						'placeholder'=>'And To',
					),
				));
			?>
		</div>
	</div>

	<div class="form-group col-md-12">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>
