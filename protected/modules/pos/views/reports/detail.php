<?php
$this->breadcrumbs=array(
	Yii::t('global','Dashboard'),
);

$this->menu=array(
	array('label'=>Yii::t('global','Dashboard'), 'url'=>array('default/index')),
	array('label'=>Yii::t('order','Create Sales'), 'url'=>array('orders/create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('order-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="col-sm-12">
<div class="panel panel-default row">
	<div class="panel-heading">
		<h4 class="panel-title">
			<?php echo Yii::t('order','Transaction By Product');?> -
			<?php echo Yii::t('global',date('l',strtotime($_GET['date'])));?>,
			<?php echo date('d',strtotime($_GET['date']));?> 
			<?php echo Yii::t('global',date('F',strtotime($_GET['date'])));?> 
			<?php echo date('Y',strtotime($_GET['date']));?> 
		</h4>
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
							'header'=>Yii::t('order','Item Name'),
							'type'=>'raw',
							'value'=>'$data->title'
						),
						array(
							'header'=>Yii::t('order','Total Item'),
							'type'=>'raw',
							'value'=>'Order::getCountOrderItemDate($_GET[\'date\'],$data->product_id)',
							'htmlOptions'=>array('style'=>'text-align:left;'),
						),
						array(
							'header'=>Yii::t('order','Price Per Item'),
							'type'=>'raw',
							'value'=>'number_format($data->price,0,\',\',\'.\')',
							'htmlOptions'=>array('style'=>'text-align:right;'),
						),
						array(
							'header'=>Yii::t('order','Sub Total'),
							'type'=>'raw',
							'value'=>'number_format(Order::getTotalOrderDate($_GET[\'date\'],$data->product_id),0,\',\',\'.\')',
							'htmlOptions'=>array('style'=>'text-align:right;'),
						),
					),
				)); ?>
		</div>
	</div>
	<div class="panel-footer">
		<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/uploads/images/xls.png').' Export To Excel',array('reports/exportExcel','date'=>$_GET['date']),array('class'=>'btn btn-success','target'=>'_newtab'));?>
	</div>
</div>
</div>
<script type="text/javascript">
$(function(){
	var total="<?php echo $total_order;?>";
	$('#order-grid').find('tbody').append('<tr><td colspan="4">TOTAL</td><td style="text-align:right;"><b>'+total+'</b></td></tr>');
});
</script>
