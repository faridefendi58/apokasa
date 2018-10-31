<div class="row">
    <div class="form-group col-md-2">
        <?php echo CHtml::activeLabelEx($model,'quantity',array('class'=>'control-label')); ?>
        <?php echo CHtml::activeTextField($model,'quantity[]',array('class'=>'form-control', 'value'=>0)); ?>
    </div>
    <div class="form-group col-md-2">
        <?php echo CHtml::activeLabelEx($model,'quantity_max',array('class'=>'control-label')); ?>
        <?php echo CHtml::activeTextField($model,'quantity_max[]',array('class'=>'form-control', 'value'=>0)); ?>
    </div>
    <div class="form-group col-md-3">
        <?php echo CHtml::activeLabelEx($model,'base_price',array('class'=>'control-label')); ?>
        <?php echo CHtml::activeTextField($model,'base_price[]',array('class'=>'form-control', 'value'=>0)); ?>
    </div>
    <div class="form-group col-md-2">
        <?php echo CHtml::activeLabelEx($model,'date_start',array('class'=>'control-label')); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'id' => uniqid(),
            'model' => $model, //Model object
            'attribute' => 'date_start[]', //attribute name
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'changeMonth' => 'true',
                'changeYear' => 'true',
                'constrainInput' => 'false'
            ),
            'htmlOptions' => array(
                'class' => 'form-control date-picker',
                'value' => date('Y-m-d'),
            ),
        ));
        ?>
    </div>
    <div class="form-group col-md-2">
        <?php echo CHtml::activeLabelEx($model,'date_end',array('class'=>'control-label')); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'id' => uniqid(),
            'model' => $model, //Model object
            'attribute' => 'date_end[]', //attribute name
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'changeMonth' => 'true',
                'changeYear' => 'true',
                'constrainInput' => 'false'
            ),
            'htmlOptions' => array(
                'class' => 'form-control date-picker',
                'value' => date('Y-m-d'),
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
                'javascript:void(0);',
                array(
                    'title' => Yii::t('product', 'Delete Discount'),
                    'onclick' => 'return removeDiscountItem(this, 0);'
                ));
            ?>
            <?php
            echo CHtml::link(
                '<i class="fa fa-plus fa-2x"></i>',
                Yii::app()->createUrl(
                    Yii::app()->controller->module->id.'/products/addDiscount',
                    array('id' => $dmodel->id)
                ),
                array('title' => Yii::t('product', 'Add Discount'), 'onclick' => 'return addDiscount(this);'))
            ?>
        </div>
    </div>
</div>