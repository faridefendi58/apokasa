<?php echo CHtml::activeLabelEx($model,'change',array('class'=>'control-label col-sm-6')); ?>
<div class="col-sm-6">
	<?php echo CHtml::activeTextField($model,'change',array('class'=>'form-control text-right','value'=>number_format($change,0,',','.'),'readOnly'=>true)); ?>
</div>
