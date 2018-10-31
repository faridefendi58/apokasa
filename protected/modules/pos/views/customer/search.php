<div class="row-fluid papper form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'id'=>uniqid(),
)); ?>
	<div class="span4">
		<div class="row">
			<?php echo $form->label($model,'name'); ?>
			<?php echo $form->textField($model,'name',array('size'=>10,'class'=>'blank')); ?>
		</div>
	</div>

	<div class="span4">
		<div class="row buttons">
			<?php 
				echo CHtml::ajaxSubmitButton(
					Yii::t('global', 'Search'),
					CHtml::normalizeUrl(array('/'.$this->route)),
					array(
						'beforeSend'=> 'js:function() { Loading.show(); }',
						'complete'=> 'js:function() { Loading.hide(); }',
						'dataType'=>'json',
						'success'=>'js:function(data){
							if(data.status=="success"){
								$("#div-for-items").empty();
								$("#div-for-items").html(data.div);
							}	
							return false;
							}'
					),
					array('style'=>'width:100px','id'=>'search-item')
				);
			?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div>
