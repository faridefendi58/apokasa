<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="form-group col-md-2 hide">
		<?php echo $form->label($model,'id',array('class'=>'control-label')); ?>
		<?php echo $form->textField($model,'id',array('class'=>'form-control')); ?>
	</div>

	<div class="form-group col-md-2">
		<?php echo $form->label($model,'customer_id',array('class'=>'control-label')); ?>
		<?php $this->widget('ext.bootstrap-select.TbSelect',array(
					'model' => $model,
					'attribute' => 'customer_id',
					'data' => Customer::items('- All Customer -'),
					'htmlOptions' => array(
					//'multiple' => true,
					'data-live-search'=>true,
					'class'=>'form-control no-margin',
				),
			)); 
		?>
	</div>

	<div class="form-group col-md-4">
		<label class="control-label col-sm-12">Date Interval</label>
		<div class="col-sm-6">
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
						'class'=>'form-control interval-date',
						'placeholder'=>'Date From',
					),
				));
			?>
		</div>
		<div class="col-sm-6">
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
						'class'=>'form-control interval-date',
						'placeholder'=>'Date To',
					),
				));
			?>
		</div>
	</div>

    <div class="form-group col-md-2">
        <label class="control-label"><?php echo Yii::t("order", "Range"); ?></label>
        <?php echo $form->dropDownList(
            $model,
            'range',
            array(
                '' => 'Semua',
                'today' => 'Hari Ini',
                'last_week' => 'Minggu Lalu',
                'this_week' => 'Minggu Ini',
                'this_month' => 'Bulan Ini',
                'last_month' => 'Bulan Lalu',
                'this_year' => 'Tahun Ini'
            ),
            array('class'=>'form-control', 'onchange' => 'filterRage(this.value);')
        ); ?>
    </div>

	<div class="form-group col-md-2">
        <label class="control-label col-sm-12">&nbsp;</label>
		<?php echo CHtml::submitButton(Yii::t('global', 'Search'), array('class'=>'btn btn-success','style'=>'min-width:120px;')); ?>
	</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
    function filterRage(the_value) {
        if (the_value.length > 0) {
            $('input.interval-date').val("");
        }
    }
    $(function () {
        $('input.interval-date').change(function () {
            $('select[id="Order_range"]').val("");
        });
    })
</script>
