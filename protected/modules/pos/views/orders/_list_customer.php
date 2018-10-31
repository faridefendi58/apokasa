<?php $this->widget('ext.bootstrap-select.TbSelect',array(
    'name'=>'find_customer',
    'data' => Customer::list_items('Pilih Pelanggan'),
    'htmlOptions' => array(
        'data-live-search'=>true,
        'class'=>'form-control no-margin',
    ),
    'value' => $selected
)); ?>
<script type="text/javascript">
    $(function () {
        $('select[id="find_customer"]').change(function(){
            pushFindCustomer(this.value,"<?php echo Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/customer/choose'); ?>");
        });
        var selected_item = "<?php echo $selected; ?>";
        setTimeout(function () {
            pushFindCustomer(selected_item,"<?php echo Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/customer/choose'); ?>");
        }, 2000);
    });
</script>
