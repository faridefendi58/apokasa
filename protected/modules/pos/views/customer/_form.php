<?php $form=$this->beginWidget('CActiveForm',array('id'=>'customer-form')); ?>

	<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'name',array('class'=>'control-label')); ?>
		<?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
	</div>

	<div class="form-group hidden">
		<?php echo $form->labelEx($model,'email',array('class'=>'control-label')); ?>
		<?php echo $form->textField($model,'email',array('class'=>'form-control')); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'telephone',array('class'=>'control-label')); ?>
		<?php echo $form->textField($model,'telephone',array('class'=>'form-control')); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'address',array('class'=>'control-label')); ?>
		<?php echo $form->textArea($model,'address',array('rows'=>3, 'cols'=>50,'class'=>'form-control')); ?>
	</div>

	<div class="form-group">
		<?php //echo CHtml::submitButton(Yii::t('global','Save'),array('style'=>'min-width:100px;','class'=>'btn btn-success')); ?>
		<?php 
		echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('/pos/customer/create')),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$(".modal-content #div-for-items").html(data.div);
						setTimeout("$(\'.modal-content .close\').click()",2000);
						reloadCustomerList();
						return false;
					}
					return false;
				}'
			),
			array('style'=>'width:100px','id'=>uniqid(),'class'=>'btn btn-success'));
		?>
	</div>

<?php $this->endWidget(); ?>
