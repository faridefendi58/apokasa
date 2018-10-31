<?php $form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
)); ?>

<div class="form-group col-md-4">
    <?php echo $form->label($model, 'id'); ?>
    <?php echo $form->textField($model, 'id'); ?>
</div>

<div class="form-group col-md-4">
    <?php echo $form->label($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 128)); ?>
</div>

<div class="form-group col-md-4">
    <?php echo $form->label($model, 'address'); ?>
    <?php echo $form->textField($model, 'address', array('size' => 60, 'maxlength' => 128)); ?>
</div>

<div class="form-group col-md-12">
    <?php echo CHtml::submitButton(Yii::t('global', 'Search')); ?>
</div>

<?php $this->endWidget(); ?>
