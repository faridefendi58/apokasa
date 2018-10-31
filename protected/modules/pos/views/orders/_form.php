<div class="row">
    <div class="col-sm-9">
        <div class="row">
            <div class="col-md-5 col-sm-6" id="list-item">
                <?php
                $use_auto_complete = true;
                if (Yii::app()->user->hasState('items_data')) {
                    $use_auto_complete = false;
                }

                if ($use_auto_complete) {
                    $this->widget('CAutoComplete', array(
                        'name' => 'scan',
                        'url' => array('orders/suggestItems'),
                        'multiple' => false,
                        'htmlOptions' => array(
                            'size' => 30,
                            'class' => 'form-control mb10',
                            'id' => 'scan',
                            'placeholder' => Yii::t('order', 'Enter Item Name Or Scan Item')
                        ),
                        'id' => uniqid(),
                        'mustMatch' => false,
                        'delay' => 30,
                        'cacheLength' => 50,
                        //'multipleSeparator'=>' ',
                        'max' => 10,
                    ));
                } else {
                    $this->widget('ext.bootstrap-select.TbSelect', array(
                        'name' => 'scan',
                        'data' => Product::list_items('Pilih Nama Barang'),
                        'htmlOptions' => array(
                            //'multiple' => true,
                            'data-live-search' => true,
                            'class' => 'form-control no-margin',
                        ),
                    ));
                }
                ?>
            </div>
            <div class="col-md-7 col-sm-6 text-right">
                <button class="btn btn-default mar_top1"
                        onclick="listItems();"><?php echo Yii::t('order', 'Advanced Search Item'); ?></button>
                <div class="col-md-4 row">
                    <?php //echo CHtml::dropDownList('currency', Yii::app()->user->getState('currency'), Currency::items(), array('class' => 'form-control', 'onchange' => 'setCurrency(this);')); ?>
                    <?php echo CHtml::dropDownList(
                            'transaction_type',
                            Yii::app()->user->getState('transaction_type'),
                            PLookup::items('TransactionType'),
                            [ 'class' => 'form-control', 'onchange' => 'setTransactionType(this);']
                    ); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive mar_top1 col-md-12 col-sm-12" id="sales-frame">
                <table class="table table-striped mb30 items">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th><?php echo Yii::t('order', 'Item Code'); ?></th>
                        <th><?php echo Yii::t('order', 'Item Name'); ?></th>
                        <th><?php echo Yii::t('order', 'Price'); ?></th>
                        <th><?php echo Yii::t('order', 'Quantity'); ?></th>
                        <th class="hidden"><?php echo Yii::t('order', 'Discount'); ?></th>
                        <th>Total</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $this->renderPartial('_items'); ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group hidden">
                    <label class="control-label">*) <?php echo CHtml::link(Yii::t('order', 'Use Promotion Code'), 'javascript:void(0);', array('onclick' => "js:$('.promocode').toggle();")); ?></label>
                    <?php echo CHtml::textField('promocode', $promocode, array('class' => 'form-control promocode', 'placeholder' => Yii::t('order', 'Please insert the promotion code'), 'style' => (Yii::app()->user->hasState('promocode')) ? 'display:block;' : 'display:none;')); ?>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo CHtml::link(Yii::t('order', 'Add Notes'), 'javascript:void(0);', array('onclick' => "js:$('.notes').toggle();")); ?></label>
                    <?php echo CHtml::textField('notes', null, array('class' => 'form-control notes', 'placeholder' => Yii::t('order', 'Please insert the notes if needed'), 'style' => 'display:none;')); ?>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 text-right" id="payment-button"
                 style="display:<?php echo (Yii::app()->user->hasState('items_belanja') && count(Yii::app()->user->getState('items_belanja')) > 0) ? 'block' : 'none'; ?>">
                <?php echo CHtml::button(Yii::t('order', 'Payment'), array('onclick' => 'payRequest("' . Yii::app()->createUrl(Yii::app()->controller->module->id . '/orders/paymentRequest') . '");', 'style' => 'min-width:100px;', 'id' => 'payment-btn', 'class' => 'btn btn-success', 'title' => 'Click "PgDn" for shortcut')); ?>
                <?php echo CHtml::button(Yii::t('order', 'Cancel Transaction'), array('onclick' => 'cancelRequest("' . Yii::app()->createUrl(Yii::app()->controller->module->id . '/orders/cancelTransaction') . '");', 'style' => 'min-width:100px;', 'id' => 'cancel-btn', 'class' => 'btn btn-default', 'title' => 'Click "F2" for shortcut')); ?>
                <div class="row-fluid mt10" id="payment-form"></div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="row">
            <div class="col-sm-12">
                <span class="fa fa-group"></span> <b><?php echo Yii::t('order', 'Select Customer (Optional)'); ?></b>
                <?php /*$this->widget('CAutoComplete', array(
					'name'=>'find_customer',
					'url'=>array('customer/suggestCustomers'),
					'multiple'=>false,
					'htmlOptions'=>array('size'=>30,'class'=>'form-control mt10 mb10','id'=>'find','placeholder'=>Yii::t('order','Please type your customer name')),
					'id'=>uniqid(),
					'mustMatch'=>false,
					'delay'=>1,
					'cacheLength'=>50,
					//'multipleSeparator'=>' ',
					'max'=>5,
				));*/ ?>
                <div class="mb10"></div>
                <div id="customer-list">
                    <?php $this->widget('ext.bootstrap-select.TbSelect', array(
                        'name' => 'find_customer',
                        'data' => Customer::list_items('Pilih Pelanggan'),
                        'htmlOptions' => array(
                            'data-live-search' => true,
                            'class' => 'form-control no-margin',
                        ),
                    )); ?>
                </div>
                <button class="btn btn-info btn-block" onclick="createCustomer();"
                        title="Click 'Ins' for shortcut"><?php echo Yii::t('order', 'Add New Customer'); ?></button>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive mt10">
                    <table class="table table-striped mb30">
                        <tbody>
                        <tr>
                            <td><b><?php echo Yii::t('order', 'Sub Total'); ?></b></td>
                            <td><h3><span id="sub-total"
                                          style="font-size:35px;"><?php echo number_format($this->getTotalBelanja(), 0, ',', '.'); ?></span>
                                </h3></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!-- row -->
<?php $this->widget('application.extensions.moneymask.MMask'); ?>
<?php
Yii::app()->clientScript->registerScript('adjust-focusing', "
	$('input[id=\"scan\"]').focus();
	$('select[id=\"scan\"]').focus();
");
Yii::app()->clientScript->registerScript('tekan', "
	$('#scan').keypress(function(e){
		if (e.which == 13) {
			pushSearch(this.value,'" . Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/scan') . "');
		}
	});
	$('select[id=\"scan\"]').change(function(){
		pushSearch(this.value,'" . Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/scan') . "');
	});
	$('#find').keypress(function(e){
		if (e.which == 13) {
			pushFindCustomer(this.value,'" . Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/customer/choose') . "');
		}
	});
	$('select[id=\"find_customer\"]').change(function(){
		pushFindCustomer(this.value,'" . Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/customer/choose') . "');
	});
	$('#promocode').keypress(function(e){
		if (e.which == 13) {
			pushFindPromoCode(this.value,'" . Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/promocode') . "');
		}
	});
");
?>

<button class="btn btn-primary btn-lg hidden" data-target="#myModal" data-toggle="modal" id="launch-modal"> Launch
    Modal
</button>
<div id="myModal" class="modal fade" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
                <h4 id="myModalLabel" class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body has-padding" id="div-for-items"> Content goes here...</div>
            <div class="modal-footer hide">
                <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                <button class="btn btn-primary" id="btn-print" type="button">Print</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function pushSearch(item, action) {
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': action,
            'type': 'post',
            'dataType': 'json',
            'data': {"item": item},
            'success': function (data) {
                if (data.status == 'success') {
                    $('#sales-frame table.items tbody').html(data.div);
                    $('span[id="sub-total"]').html(data.subtotal);
                    $('#payment-button').css('display', 'block');
                    $("input[id=\'scan\']").val("");
                    $('#list-item').find("input.input-block-level").val("");
                    $('#list-item').find(".filter-option").html('Pilih Nama Barang');

                    var qtyInput = $("input[id='qty']:last-child");
                    qtyInput.focus();
                    qtyInput.select();
                } else {
                    $("input[id=\'scan\']").val("");
                    $("input[id=\'scan\']").focus();
                }
            },
        });
        return false;
    }

    function pushQty(id, data, action) {
        var $this = $(data);
        var qty = data.value;
        if (typeof qty == "undefined")
            var qty = $this.val();
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': action,
            'type': 'post',
            'dataType': 'json',
            'data': {"id": id, "qty": qty},
            'success': function (data) {
                if (data.status == 'success') {
                    $('input[id="qty"]').html(data.div);
                    $('span[id="sub-total"]').html(data.subtotal);
                    $('#discount-' + id).html(data.discount);
                    $('#total-item-' + id).html(data.total);
                    $('#unit-price-' + id).html(data.unit_price);
                    //if($('#promocode').val().length>0)
                    //window.location.reload(true);
                    $("input[id=\'scan\']").focus();
                } else
                    $this.val(1);
                //alert(data.message);
            },
        });
        return false;
    }

    function checkSearch(data, action) {
        if (data.value !== '') {
            pushSearch(data.value, action);
        }
    }

    function payRequest(action) {
        var id = "<?php echo $_GET['id'];?>";//when update request
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': action,
            'type': 'post',
            'dataType': 'json',
            'data': {'id': id},
            'success': function (data) {
                if (data.status == 'success') {
                    $('#payment-form').html(data.div);
                    $('input[id="PaymentForm_amount_tendered"]').focus();
                    $('input[id="PaymentForm_amount_tendered"]').select();
                }
            },
        });
        return false;
    }

    function changeRequest(amount_tendered, action) {
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': action,
            'type': 'post',
            'dataType': 'json',
            'data': {"amount_tendered": amount_tendered},
            'success': function (data) {
                if (data.status == 'success') {
                    $('#change').html(data.div);
                    $('.submit-payment').removeAttr('disabled');
                    $('#submit-payment-area').css('display', 'block');
                    //$('.submit-payment').focus();
                    $('#payment-btn-help').focus();
                } else
                    alert(data.div);
            },
        });
        return false;
    }

    function deleteItem(id) {
        if (confirm('Are you sure to delete this item ?')) {
            $.ajax({
                'beforeSend': function () {
                    Loading.show();
                },
                'complete': function () {
                    Loading.hide();
                },
                'url': "<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/deleteItem');?>",
                'type': 'post',
                'dataType': 'json',
                'data': {"id": id},
                'success': function (data) {
                    if (data.status == 'success') {
                        $('#sales-frame table.items tbody').html(data.div);
                        $('span[id="sub-total"]').html(data.subtotal);
                        if (data.count <= 0)
                            $('#payment-button').css('display', 'none');

                        $("input[id=\'scan\']").focus();
                    }
                },
            });
            return false;
        }
    }

    function cancelRequest() {
        if (confirm('Are you sure to cancel transactions ?')) {
            $.ajax({
                'beforeSend': function () {
                    Loading.show();
                },
                'complete': function () {
                    Loading.hide();
                },
                'url': "<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/cancelTransaction');?>",
                'type': 'post',
                'dataType': 'json',
                'success': function (data) {
                    if (data.status == 'success') {
                        $('#sales-frame table.items tbody').html(data.div);
                        $('span[id="sub-total"]').html(data.subtotal);
                        $('#payment-form').empty();
                        $('#payment-button').css('display', 'none');
                        $("input[id=\'scan\']").focus();
                        $("#promocode").val('');
                        $("#promocode").hide();
                        window.location.reload(true);
                    }
                },
            });
            return false;
        }
    }

    function listItems() {
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': "<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/viewItems');?>",
            'type': 'post',
            'dataType': 'json',
            'success': function (data) {
                if (data.status == 'success') {
                    $('.modal-content .modal-footer').hide();
                    $('.modal-content .modal-title').html("<?php echo Yii::t('order', 'List Items');?>");
                    $('.modal-content #div-for-items').html(data.div);
                    $('#launch-modal').trigger('click');
                    $("input[id=\'items_name\']").focus();
                }
            },
        });
        return false;
    }

    function createCustomer() {
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': "<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/customer/create');?>",
            'type': 'post',
            'dataType': 'json',
            'success': function (data) {
                if (data.status == 'success') {
                    $('.modal-content .modal-footer').hide();
                    $('.modal-content .modal-title').html("<?php echo Yii::t('order', 'Add New Customer');?>");
                    $('.modal-content #div-for-items').html(data.div);
                    $('#launch-modal').trigger('click');
                    $("form[id='customer-form']").find("input[id='Customer_name']").focus();
                }
            },
        });
        return false;
    }

    function lookupCustomer() {
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': "<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/customers/view');?>",
            'type': 'post',
            'dataType': 'json',
            'success': function (data) {
                if (data.status == 'success') {
                    $('#dialogItems #div-for-items').html(data.div);
                    $('#ui-dialog-title-dialogItems').html('Customers');
                    $('#dialogItems').dialog('open');
                }
            },
        });
        return false;
    }

    function pushFindCustomer(nama, url) {
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': url,
            'type': 'post',
            'data': {'nama': nama},
            'dataType': 'json',
            'success': function (data) {
                console.log(data);
                if (data.status == 'success') {
                    $('#customer-name').html(data.div);
                    $('#customer-name').css('display', 'block');
                    $('#find-customer').css('display', 'none');
                    $("input[id=\'scan\']").focus();
                }
            },
        });
        return false;
    }

    function pushFindPromoCode(promocode, url) {
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': url,
            'type': 'post',
            'data': {'promocode': promocode},
            'dataType': 'json',
            'success': function (data) {
                if (data.status == 'success') {
                    $('#sales-frame table.items tbody').html(data.cart);
                    $('#sub-total').html(data.subtotal);
                    $("input[id=\'scan\']").val("");
                }
            },
        });
        return false;
    }

    function pushDiscount(data) {
        var $this = $(data);
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': "<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/createDiscount');?>",
            'type': 'post',
            'data': {'id': $this.attr('rel_id'), 'value': $this.val()},
            'dataType': 'json',
            'success': function (data) {
                if (data.status == 'success') {
                    var url = "<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/updateQty');?>";
                    pushQty($this.attr('rel_id'), $this.parent().parent().find('#qty'), url);
                }
            },
        });
        return false;
    }

    function setCurrency(data) {
        var $this = $(data);
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': "<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/setCurrency');?>",
            'type': 'post',
            'data': {'value': $this.val()},
            'dataType': 'json',
            'success': function (data) {
                if (data.status == 'success') {
                    window.location.reload(true);
                }
            },
        });
        return false;
    }

    function reloadCustomerList() {
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': "<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/listCustomer');?>",
            'type': 'post',
            'dataType': 'json',
            'success': function (data) {
                console.log(data);
                if (data.status == 'success') {
                    $('#customer-list').html(data.div);
                }
            },
        });
        return false;
    }

    function setTransactionType(data) {
        var $this = $(data);
        $.ajax({
            'beforeSend': function () {
                Loading.show();
            },
            'complete': function () {
                Loading.hide();
            },
            'url': "<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/setTransactionType');?>",
            'type': 'post',
            'dataType': 'json',
            'data': {'value': $this.val()},
            'success': function (data) {
                console.log(data);
                if (data.status == 'success') {
                    window.location.reload(true);
                }
            },
        });
        return false;
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        function setfocus() {
            $("input[id=\'items_name\']").focus();
        }

        document.onkeydown = function (e) {
            //console.log(e.keyCode);
            setfocus();
            //alert(e.keyCode);
            if (e.keyCode == 33) {//--Tombol_PgUp---
                //$('#find-btn').click();
                //listItems();
                $("#list-item").find('button.selectpicker').click();
            }
            /*else if (e.keyCode==9){//--Tombol_TAB---
                $('#payment-btn').click();
            }*/
            else if (e.keyCode == 34) {//--Tombol_PgDn---
                $('#payment-btn').click();
            }
            /*else if (e.keyCode==18){//--Tombol_ALT---
                $('#cancel-btn').click();
            }*/
            else if (e.keyCode == 113) {//--Tombol_F2---
                $('#cancel-btn').click();
            }
            else if (e.keyCode == 27) {//--Tombol_ESC---
                //$('#dialogItems').dialog('close');
                $('button[data-dismiss="modal"]').trigger('click');
                $('#cancel-btn').click();
            }
            else if (e.keyCode == 36) {//--Tombol_Home---
                lookupCustomer();
            }
            else if (e.keyCode == 45) {//--Tombol_Ins---
                createCustomer();
            }
            else if (e.keyCode == 112) {//--Tombol_F1---
                $('#find-customer').toggle();
                //$("input[id=\'find\']").focus();
            }
            else {
                setfocus();
            }
        }
        // trigger the notes
        $('input[id="notes"]').change(function () {
            $("input[id='PaymentForm_notes']").val($(this).val());
        });
    });
</script>
