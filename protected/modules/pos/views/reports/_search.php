<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="form-group">
		<label class="control-label col-sm-12">Date Interval</label>
		<div class="col-sm-3">
			<?php
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$model,
					'attribute'=>'date_from', //attribute name
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'yy-mm-dd',
						'changeMonth' => 'true',
						'changeYear'=>'true',
						'constrainInput' => 'false'
					),
					'htmlOptions'=>array(
						'class'=>'form-control',
						'placeholder'=>'Date From',
						'value'=>date("Y-m-d",time()-(24*3600)),
					),
				));
			?>
		</div>
		<div class="col-sm-3">
			<?php
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$model,
					'attribute'=>'date_to', //attribute name
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'yy-mm-dd',
						'changeMonth' => 'true',
						'changeYear'=>'true',
						'constrainInput' => 'false'
					),
					'htmlOptions'=>array(
						'class'=>'form-control',
						'placeholder'=>'Date To',
						'value'=>date("Y-m-d"),
					),
				));
			?>
		</div>

	<div class="form-group col-sm-6">
		<?php echo CHtml::submitButton('Search',array('class'=>'btn btn-success','style'=>'min-width:120px;')); ?>
	</div>
	</div>

<?php $this->endWidget(); ?>
