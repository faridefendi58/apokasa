<div class="hide">
<?php
	$this->widget('ext.mPrint.mPrint', array(
 		'title' => $model->invoiceFormatedNumber,        //the title of the document. Defaults to the HTML title
		'tooltip' => 'testing',    //tooltip message of the print icon. Defaults to 'print'
		'text' => 'Print Invoice', //text which will appear beside the print icon. Defaults to NULL
		'element' => '.table-fixed',      //the element to be printed.
		/*'exceptions' => array(     //the element/s which will be ignored
			'.link-view',
		),*/
		'publishCss' => 'true',       //publish the CSS for the whole page?
		));
	?>
</div>
<div class="table-fixed">
<table class="table">
	<tr>
		<td colspan="2">
			<center>
				<?php echo CHtml::image(Yii::app()->request->baseUrl.'/uploads/images/'.Yii::app()->config->get('struk_logo'),'',array('style'=>'max-width:200px;'));?>
			</center>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="bordered-bottom">
			<center><small>
				<?php echo strtoupper(Yii::app()->config->get('site_name'));?><br/>
				<?php echo strtoupper(Yii::app()->config->get('address'));?></small>
			</center>
		</td>
	</tr>
	<tr>
		<td class="bordered-bottom" colspan="2" style="width:100%;"><small><?php echo date("d.m.y-H:i",strtotime($model->date_entry));?></small></td>
	</tr>
</table>
<table class="table">
	<?php foreach($model->items_rel as $item):?>
	<tr>
		<td colspan="4" class="no-padding"><small><?php echo strtoupper($item->title);?></small></td>
	</tr>
	<tr>
		<td width="20%;" class="no-padding">&nbsp;</td>
		<td class="text-center no-padding"><small><?php echo $item->quantity;?></small></td>
		<td class="text-right no-padding"><small><?php echo number_format($item->orderPrice,0,',','.');?></small></td>
		<td class="text-right no-padding"><small><?php echo number_format($item->price,0,',','.');?></small></td>
	</tr>
	<?php endforeach;?>
	<tr>
		<td class="no-border">&nbsp;</td>
		<td colspan="2" class="text-right bordered-bottom"><small>DISKON :</small></td>
		<td class="text-right bordered-bottom"><small><?php echo number_format($model->totalDiscount,0,',','.');?></small></td>
	</tr>
	<tr>
		<td class="no-border">&nbsp;</td>
		<td colspan="2" class="text-right bordered-bottom"><small>HARGA JUAL :</small></td>
		<td class="text-right bordered-bottom"><small><?php echo number_format($model->totalPrice,0,',','.');?></small></td>
	</tr>
	<tr class="lower">
		<td class="no-border">&nbsp;</td>
		<td colspan="2" class="text-right"><small>TOTAL :</small></td>
		<td class="text-right"><small><?php echo number_format($model->totalPrice,0,',','.');?></small></td>
	</tr>
	<tr class="lower">
		<td class="no-border">&nbsp;</td>
		<td colspan="2" class="text-right"><small>TUNAI :</small></td>
		<td class="text-right"><small><?php echo number_format($model->cash,0,',','.');?></small></td>
	</tr>
	<tr class="lower">
		<td class="no-border">&nbsp;</td>
		<td colspan="2" class="text-right"><small>KEMBALI :</small></td>
		<td class="text-right"><small><?php echo number_format($model->change,0,',','.');?></small></td>
	</tr>
	<tr>
		<td colspan="4" class="no-border">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" class="no-border"><center><small><?php echo Yii::app()->config->get('thanks_note');?></small></center></td>
	</tr>
</table>
</div>
<style>
@media print {
@page{margin:0;size:portrait;}
/*@page:first{
	margin-top:0cm;
}
@page:last{
	margin-top:0cm;
}*/
.text-right {text-align:right;}
.text-left {text-align:left;}
.text-center {text-align:center;}
.border-top {border-top:2px dotted #000;}
.bordered-top-bottom {border:2px 0 2px 0 dotted #000;}
.bordered-bottom {border-bottom:2px dotted #000;}
.no-padding{padding:0px;}
.table {max-width:58mm;margin:0px;}
}
.table-fixed td {border-top: 0 solid #ddd !important;}
.no-border {border:none !important;}
.modal-dialog {
  margin: 0px auto;
  width: 300px;
}
.modal {
  top: 5px;
}
.border-top {border-top:2px dotted #000;}
.bordered-top-bottom {border:2px 0 2px 0 dotted #000;}
.bordered-bottom {border-bottom:2px dotted #000;}
.table-fixed .table > tbody > tr > td, .table > tbody > tr > th, .table-fixed .table > tfoot > tr > td, .table-fixed .table > tfoot > tr > th, .table-fixed .table > thead > tr > td, .table-fixed .table-fixed > thead > tr > th {
  padding: 5px;
  vertical-align: middle;
}
</style>
<script type="text/javascript">
$(function(){
	var print = "<?php echo $print;?>";
	if(print){
	setTimeout(function(){
		$('#mprint').trigger('click');
	}, 1000);
	}
});
$('#btn-print').click(function(){
	$('#mprint').trigger('click');
	$('button[data-dismiss="modal"]').trigger('click');
	$('input[id="scan"]').focus();
	return false;
});
</script>
