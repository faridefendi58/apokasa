<ul class="nav nav-tabs">
	<li class="active">
		<a data-toggle="tab" href="#general">
			<strong><?php echo Yii::t('order','General Information');?></strong>
		</a>
	</li>
	<li class="">
		<a data-toggle="tab" href="#price">
			<strong><?php echo Yii::t('order','Product Price');?></strong>
		</a>
	</li>
    <li class="" <?php if ($model->isNewRecord): ?>style="display: none;"<?php endif; ?>>
        <a data-toggle="tab" href="#discount">
            <strong><?php echo Yii::t('order','Product Discount');?></strong>
        </a>
    </li>
</ul>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'promo-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="tab-content pill-content">
	<div id="general" class="tab-pane active">
		<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model,'name',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
		</div>

		<div class="form-group col-md-4 mb20">
			<?php echo $form->labelEx($model,'type',array('class'=>'control-label')); ?>
			<?php echo $form->dropDownList($model,'type',ProductType::items(Yii::t('product','- Choose Type -')),array('class'=>'form-control')); ?>
		</div>

		<div class="form-group col-md-8">
			<?php echo $form->labelEx($model,'description',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model,'description',array('class'=>'form-control')); ?>
		</div>

		<?php if(!$model->isNewRecord):?>
		<div class="form-group col-md-8">
			<?php echo $form->labelEx($model,'tag',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model,'tag',array('class'=>'form-control')); ?>
			<p class="hint"><?php echo Yii::t('global','Please separate different tags with commas.');?></p>
		</div>
		<?php endif;?>
	</div>
	<div id="price" class="tab-pane">
		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model2,'purchase_date',array('class'=>'control-label')); ?>
			<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model2, //Model object
						'attribute'=>'purchase_date', //attribute name
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>'yy-mm-dd',
							'changeMonth' => 'true',
							'changeYear'=>'true',
							'constrainInput' => 'false'
						),
						'htmlOptions'=>array(
							'class'=>'form-control',
							'value'=>(!empty($model2->purchase_date))? date('Y-m-d',strtotime($model2->purchase_date)) : date('Y-m-d'),
						),
					));
			?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model2,'purchase_price',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model2,'purchase_price',array('class'=>'form-control')); ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model2,'purchase_stock',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model2,'purchase_stock',array('class'=>'form-control')); ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model2,'current_stock',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model2,'current_stock',array('class'=>'form-control')); ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $form->labelEx($model2,'sold_price',array('class'=>'control-label')); ?>
			<?php echo $form->textField($model2,'sold_price',array('class'=>'form-control')); ?>
		</div>
	</div>
    <div id="discount" class="tab-pane">
        <div id="discount-container">
        <?php if(count($model->discount_rel) > 0): ?>
            <?php $i = 1; ?>
            <?php foreach ($model->discount_rel as $dmodel): ?>
                <div class="row">
                    <div class="form-group col-md-2">
                        <?php echo $form->labelEx($dmodel,'quantity',array('class'=>'control-label')); ?>
                        <?php echo $form->textField($dmodel,'quantity['.$dmodel->id.']',array('class'=>'form-control', 'value' => $dmodel->quantity)); ?>
                    </div>
                    <div class="form-group col-md-2">
                        <?php echo $form->labelEx($dmodel,'quantity_max',array('class'=>'control-label')); ?>
                        <?php echo $form->textField($dmodel,'quantity_max['.$dmodel->id.']',array('class'=>'form-control', 'value' => $dmodel->quantity_max)); ?>
                    </div>
                    <div class="form-group col-md-3">
                        <?php echo $form->labelEx($dmodel,'base_price',array('class'=>'control-label')); ?>
                        <?php echo $form->textField($dmodel,'base_price['.$dmodel->id.']',array('class'=>'form-control', 'value' => $dmodel->base_price)); ?>
                    </div>
                    <div class="form-group col-md-2">
                        <?php echo $form->labelEx($dmodel,'date_start',array('class'=>'control-label')); ?>
                        <?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'id' => uniqid(),
                            'model' => $dmodel, //Model object
                            'attribute' => 'date_start['.$dmodel->id.']', //attribute name
                            'options' => array(
                                'showAnim' => 'fold',
                                'dateFormat' => 'yy-mm-dd',
                                'changeMonth' => 'true',
                                'changeYear' => 'true',
                                'constrainInput' => 'false'
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                                'value' => (!empty($dmodel->date_start))? date('Y-m-d',strtotime($dmodel->date_start)) : date('Y-m-d'),
                            ),
                        ));
                        ?>
                    </div>
                    <div class="form-group col-md-2">
                        <?php echo $form->labelEx($dmodel,'date_end',array('class'=>'control-label')); ?>
                        <?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'id' => uniqid(),
                            'model' => $dmodel, //Model object
                            'attribute' => 'date_end['.$dmodel->id.']', //attribute name
                            'options' => array(
                                'showAnim' => 'fold',
                                'dateFormat' => 'yy-mm-dd',
                                'changeMonth' => 'true',
                                'changeYear' => 'true',
                                'constrainInput' => 'false'
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                                'value' => (!empty($dmodel->date_end))? date('Y-m-d',strtotime($dmodel->date_end)) : date('Y-m-d'),
                            ),
                        ));
                        ?>
                    </div>
                    <div class="form-group col-md-1">
                        <label class="control-label">&nbsp;</label>
                        <div class="text-center">
                            <?php
                            echo CHtml::link(
                                '<i class="fa fa-trash-o fa-2x"></i>',
                                Yii::app()->createUrl(
                                    Yii::app()->controller->module->id.'/products/deleteDiscount',
                                    array('id' => $dmodel->id)
                                ),
                                array(
                                        'title' => Yii::t('product', 'Delete Discount'),
                                        'onclick' => 'return removeDiscountItem(this, "'.$dmodel->id.'");'
                                    ))
                            ?>
                            <?php if ($i == count($model->discount_rel)) : ?>
                                <?php
                                echo CHtml::link(
                                    '<i class="fa fa-plus fa-2x"></i>',
                                    Yii::app()->createUrl(
                                        Yii::app()->controller->module->id.'/products/addDiscount',
                                        array('id' => $dmodel->id)
                                    ),
                                    array('title' => Yii::t('product', 'Add Discount'), 'onclick' => 'return addDiscount(this);'))
                                ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php echo $form->hiddenField($dmodel, 'id['.$dmodel->id.']', array('value' => $dmodel->id)); ?>
                </div>
                <?php $i++; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <?php echo $this->renderPartial('_discount', array('model' => $model3)); ?>
        <?php endif; ?>
            </div>
    </div>
</div>
<div class="form-group col-md-12 mt10">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    function addDiscount(dt)
    {
        $.ajax({
            'beforeSend': function() { Loading.show(); },
            'complete': function() { Loading.hide(); },
            'url': $(dt).attr('href'),
            'type':'post',
            'dataType': 'json',
            'success': function(data){
                if(data.status=='success'){
                    $(dt).hide();
                    $('#discount-container').append(data.div);
                }
            },
        });
        return false;
    }

    function removeDiscountItem(dt, id) {
        if (confirm("<?php echo Yii::t('global', 'Are you sure you want to delete this?'); ?>")) {
            if (id > 0) {
                $.ajax({
                    'beforeSend': function() { Loading.show(); },
                    'complete': function() { Loading.hide(); },
                    'url': $(dt).attr('href'),
                    'type':'post',
                    'dataType': 'json',
                    'data': {'id':id},
                    'success': function(data){
                        console.log(data);
                        if(data.status=='success'){
                            $(dt).parent().parent().parent().remove();
                        }
                    },
                });
            } else {
                $(dt).parent().parent().parent().remove();
            }
        }

        return false;
    }
</script>
