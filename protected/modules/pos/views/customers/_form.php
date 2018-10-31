<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'customer-form',
    'enableAjaxValidation' => false,
)); ?>

<p class="note"><?php echo Yii::t('global', 'Fields with <span class="required">*</span> are required.'); ?></p>

<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-warning alert-block alert-dismissable fade in')); ?>

<div class="col-md-6">
    <div class="form-group">
        <?php echo $form->labelEx($model, 'name', array('class' => 'control-label')); ?>
        <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'email', array('class' => 'control-label')); ?>
        <?php echo $form->textField($model, 'email', array('class' => 'form-control')); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'telephone', array('class' => 'control-label')); ?>
        <?php echo $form->textField($model, 'telephone', array('class' => 'form-control')); ?>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <?php echo $form->labelEx($model, 'address', array('class' => 'control-label')); ?>
        <?php echo $form->textArea($model, 'address', array('class' => 'form-control', 'rows' => 10)); ?>
    </div>
</div>

<div class="form-group col-md-12">
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global', 'Create') : Yii::t('global', 'Save'), array('style' => 'min-width:100px;')); ?>
</div>

<?php $this->endWidget(); ?>
