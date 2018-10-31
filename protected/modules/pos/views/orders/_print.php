<?php
	$this->widget('ext.mPrint.mPrint', array(
 		'title' => 'Transaksi pembelian '.date("d F Y"),        //the title of the document. Defaults to the HTML title
		'tooltip' => 'testing',    //tooltip message of the print icon. Defaults to 'print'
		'text' => 'Print Results', //text which will appear beside the print icon. Defaults to NULL
		'element' => '#div-for-print',      //the element to be printed.
		/*'exceptions' => array(     //the element/s which will be ignored
			'.link-view',
		),*/
		'publishCss' => 'false',       //publish the CSS for the whole page?
		));
	?>
<div id="div-for-print" class="cetak">
	<div class="grid-cetak">
	<table class="items-no-border">
		<tr>
			<td><b><?php echo Yii::app()->config->get('site_name');?></b></td>
			<td style="text-align:center;" class="no-border"><?php echo date("d F Y H:i");?></td>
		</tr>
		<tr>
			<td><?php //echo Yii::app()->config->get('address');?></td>
		</tr>
	</table>
	<br class="clear"/>
	<table class="items">
		<tr>
			<th>No</th>
			<th>Nama Barang</th>
			<th>Harga Satuan</th>
			<th>Jml</th>
			<th>Diskon</th>
			<th>Sub Total</th>
		</tr>
	<?php 
	if(Yii::app()->user->hasState('items_belanja')):
		$no=1; $tot_qty=0; $tot_disc=0; $tot_pay=0;
		foreach(Yii::app()->user->getState('items_belanja') as $index=>$data){?>
			<?php 
			$harga_bruto=$data['unit_price']*$data['qty'];
			$harga_netto=$harga_bruto-$data['discount'];
			$discount=$harga_bruto-$harga_netto;
			$tot_qty=$tot_qty+$data['qty'];
			//$tot_disc=$tot_disc+$discount;
			$tot_pay=$tot_pay+$harga_netto;
			?>
			<tr>
				<td style="text-align:center;"><?php echo $no;?></td>
				<td><?php echo $data['name'];?></td>
				<td style="text-align:right;"><?php echo number_format($data['unit_price'],0,',','.');?></td>
				<td style="text-align:center;"><?php echo $data['qty'];?></td>
				<td style="text-align:right;"><?php echo number_format($discount,0,',','.');?></td>
				<td style="text-align:right;" id="total-item-<?php echo $index;?>"><?php echo number_format($harga_netto,0,',','.');?></td>
			</tr>
	<?php
			$no++;
		}?>
		<tr>
			<td colspan="3">Total</td>
			<td style="text-align:center;"><?php echo $tot_qty;?></td>
			<td style="text-align:right;">&nbsp;</td>
			<td style="text-align:right;"><?php echo number_format($tot_pay,0,',','.');?></td>
		</tr>
	<?php
	endif;
	?>
	</table>
	<br class="clear"/>
	<table class="items-no-border">
		<tr>
			<td width="70%">&nbsp;</td>
			<td>Dibayarkan</td>
			<td style="text-align:right;"><?php echo number_format($amount_tendered,0,',','.');?></td>
		</tr>
		<tr>
			<td width="70%">&nbsp;</td>
			<td>Kembali</td>
			<td style="text-align:right;"><?php echo number_format($change,0,',','.');?></td>
		</tr>
	</table>
	</div>
</div>
<style>
@media print {
.grid-cetak
{
	padding: 15px 0;
}

.grid-cetak table.items
{
	background: none;
	border-collapse: collapse;
	width: 100%;
	border: 1px #000 solid;
}

.grid-cetak table.items th, .grid-cetak table.items td
{
	font-size: 0.9em;
	border: 1px #000 solid;
	padding: 0.3em;
}

.grid-cetak table.items th
{
	color: white;
	text-align: center;
	padding:5px 0px 5px 0px;
}

.grid-cetak table.items-no-border
{
	background: none;
	width: 100%;
	border: none;
}
}
</style>
