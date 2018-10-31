<?php
$this->breadcrumbs=array(
	Yii::t('global','Dashboard'),
);

$this->menu=array(
	array('label'=>Yii::t('global','Dashboard'), 'url'=>array('default/index')),
	array('label'=>Yii::t('order','Create Sales'), 'url'=>array('orders/create')),
);
?>
<div class="col-sm-12">
<div class="panel panel-default row">
	<div class="panel-heading">
		<h4 class="panel-title">Antrian Laporan</h4>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$dataProvider,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'order-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
							'htmlOptions'=>array('style'=>'text-align:center;'),
						),
						array(
							'name'=>'invoice_id',
							'type'=>'raw',
							'value'=>'CHtml::link($data->invoice_rel->invoiceFormatedNumber,array(\'invoices/update\',\'id\'=>$data->id))'
						),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>'$data->date_entry'
						),
						array(
							'name'=>'user_entry',
							'type'=>'raw',
							'value'=>'$data->user_entry_rel->username'
						),
					),
				)); ?>
		</div>
		<?php if($dataProvider->totalItemCount>0):?>
		<?php echo CHtml::link('Upload Laporan','javascript:void(0);',array('class'=>'btn btn-success','id'=>'upload-laporan'));?>
		<?php endif;?>
	</div>
</div>
</div>
<script type="text/javascript">
$('#upload-laporan').click(function(){
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url':"<?php echo Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/reports/push');?>",
		'type':'post',
		'dataType':'json',
		'async':true,
		'success':function(data){
			if(data.status=='success'){
				$('.table-responsive').html(data.div);
			}
		}
	});
	return false;
});
</script>
