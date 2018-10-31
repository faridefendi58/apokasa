<div class="row">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'post',
	'id'=>uniqid(),
)); ?>
	<div class="form-group col-sm-6">
		<?php echo $form->label($model,'barcode',array('class'=>'control-label')); ?>
		<?php echo $form->textField($model,'barcode',array('size'=>10,'class'=>'form-control','value'=>$default['Items']['barcode'])); ?>
	</div>
	<div class="form-group col-sm-6">
		<?php echo $form->label($model,'name',array('class'=>'control-label')); ?>
		<?php echo CHtml::textField('items_name',$default['items_name'],array('size'=>30,'class'=>'form-control')); ?>
	</div>
	<div class="form-group col-sm-4">
		<?php 
				echo CHtml::ajaxSubmitButton(
					Yii::t('global', 'Search'),
					CHtml::normalizeUrl(array('/'.$this->route)),
					array(
						'beforeSend'=> 'js:function() { Loading.show(); }',
						'complete'=> 'js:function() { Loading.hide(); }',
						'dataType'=>'json',
						'success'=>'js:function(data){
							if(data.status=="success")
								$("#div-for-items").html(data.div);
								$("#items_name").focus();
							return false;
							}'
					),
					array('style'=>'min-width:120px','id'=>'search-item','class'=>'btn btn-info')
				);
		?>
	</div>

<?php $this->endWidget(); ?>
</div>
