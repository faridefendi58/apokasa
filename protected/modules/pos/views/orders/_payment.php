<?php $form = $this->beginWidget('CActiveForm', array('action' => false, 'htmlOptions' => array('class' => 'form-horizontal padding5'))); ?>

<?php echo $form->errorSummary($model); ?>

<div class="form-group">
    <?php echo $form->labelEx($model, 'type', array('class' => 'control-label col-sm-6')); ?>
    <div class="col-sm-6">
        <?php echo $form->dropDownList($model, 'type', PLookup::items('OrderType'), array('class' => 'form-control')); ?>
    </div>
</div>
<div class="form-group" id="amount-tendered">
    <?php echo $form->labelEx($model, 'amount_tendered', array('class' => 'control-label col-sm-6')); ?>
    <div class="col-sm-6">
        <?php echo $form->textField($model, 'amount_tendered', array('class' => 'form-control text-right', 'value' => $this->getTotalBelanja())); ?>
    </div>
</div>

<div class="form-group mar_top1" id="change"></div>

<?php echo $form->hiddenField($model, 'notes'); ?>

<div class="form-group submit mar_top1" id="submit-payment-area" style="display:none;">
    <div class="col-sm-12">
        <?php echo CHtml::button(Yii::t('order', 'Save Transaction'), array('id' => 'payment-btn-help', 'class' => 'btn btn-info')); ?>
        <?php
        if (empty($id))
            $url = CHtml::normalizeUrl(array('/' . Yii::app()->controller->module->id . '/orders/paymentRequest'));
        else
            $url = CHtml::normalizeUrl(array('/' . Yii::app()->controller->module->id . '/orders/paymentRequestUpdate/id/' . $id));
        echo CHtml::ajaxSubmitButton(Yii::t('order', 'Save Transaction'), $url, array('dataType' => 'json', 'success' => 'js:
					function(data){
		                console.log(data);
						if(data.status=="success"){
							$("#change").empty();
							$("#sub-total").html(0);
							$("#sales-frame table.items tbody").empty();
							$("#payment-form").empty();
							$("#payment-button").css("display","none");
							$("input[id=\'scan\']").val();
							$("input[id=\'scan\']").focus();
							$("#customer-name").css("display","none");
							$("#promocode").val("");
							$("#promocode").hide();
							//auto print struk after transaction complete
							//printStruk(data.invoice_id);
							setTimeout(function(){
							    window.location.reload(true);
							}, 2000);
						}
						return false;
					}'
        ),
            array('style' => 'min-width:100px;display:none;', 'id' => uniqid(), 'class' => 'submit-payment btn btn-info', 'disabled' => true));
        ?>
        <?php echo CHtml::link(Yii::t('order', 'Print Transaction'), array('/' . Yii::app()->controller->module->id . '/orders/print'), array('id' => 'print-struk', 'class' => 'btn btn-default hide')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>

<?php $this->widget('application.extensions.moneymask.MMask'); ?>
<?php
Yii::app()->clientScript->registerScript('press', "
	$('input[id=\"PaymentForm_amount_tendered\"]').keypress(function(e){
		e.preventDefault();
		if (e.which == 13) {
			changeRequest(this.value,'" . Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/changeRequest') . "');
		}
	});
	var current_val=$('input[id=\"PaymentForm_amount_tendered\"]').val();
	$('input[id=\"PaymentForm_amount_tendered\"]').blur(function() {
		if($(this).val() == '')
		      	$(this).val(current_val);
	});
	$('a[id=\"print-struk\"]').click(function(){
		var tendered=$('#PaymentForm_amount_tendered').val();
		$.ajax({
			'beforeSend': function() { Loading.show(); },
			'complete': function() { Loading.hide(); },
			'url': this.href,
			'dataType': 'json',
			'type':'post',
			'data':{'amount_tendered':tendered},
			'success': function(data){
				if(data.status=='success')
					$('#div-for-items').html(data.div);
					$('.modal-title').html('Detail Pembelian');
					$('#launch-modal').trigger('click');
				},
		});
		return false;
	});
	$('#PaymentForm_type').change(function(){
		if($(this).val()==0){
			$('#amount-tendered').hide();
			$('#PaymentForm_amount_tendered').val($('#sub-total').val());
			$('#submit-payment-area').show();
			$('#change').hide();
			//$('.submit-payment').removeAttr('disabled');
		}else{
			$('#amount-tendered').show();
			$('#submit-payment-area').hide();
			$('#change').show();
			//$('.submit-payment').attr('disabled','disabled');
		}
	});
	$('#payment-btn-help').click(function(){
		$('.submit-payment').removeAttr('disabled');
		$('.submit-payment').trigger('click');
		return false;
	});
");
Yii::app()->clientScript->registerScript('js-mask-amount', "
	$('#PaymentForm_amount_tendered').maskMoney({symbol:'', showSymbol:false, thousands:'.', decimal:',', symbolStay: true,precision:0});
");
?>
<script type="text/javascript">
    function printStruk(id) {
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': "<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/invoices/printPreview');?>/id/" + id,
            'type': 'post',
            'dataType': 'json',
            'data': {'new_order': true},
            'success': function (data) {
                if (data.status == 'success') {
                    /*$('.modal-content .modal-header').hide();
                    $('.modal-footer').removeClass('hide');
                    $('.modal-footer').show();*/
                    $('#div-for-items').html(data.div);
                    /*$('#launch-modal').trigger('click');
                    $("#btn-print").attr("tabindex",-1).focus();*/
                }
            }
        });
        return false;
    }
</script>
