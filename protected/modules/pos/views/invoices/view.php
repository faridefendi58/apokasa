<?php
$this->breadcrumbs=array(
	Yii::t('order','Invoices')=>array('view'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array(
		'label'=>Yii::t('global','List').' '.Yii::t('order','Invoices'), 
		'url'=>array('view'),
		'visible'=>RbacUserAccess::isChecked(Yii::app()->controller->module->id,'invoices',Yii::app()->user->id,'read_p')
	),
	array(
		'label'=>Yii::t('global','Create').' '.Yii::t('order','Orders'), 
		'url'=>array('orders/create'),
		'visible'=>RbacUserAccess::isChecked(Yii::app()->controller->module->id,'orders',Yii::app()->user->id,'create_p')
	),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('all-grid', {
		data: $(this).serialize()
	});
	$.fn.yiiGridView.update('paid-grid', {
		data: $(this).serialize()
	});
	$.fn.yiiGridView.update('unpaid-grid', {
		data: $(this).serialize()
	});
	$.fn.yiiGridView.update('refund-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<ul class="row stats">
	<?php /*
    <li class="col-xs-3">
		<a class="btn btn-default" href="#paid-invoice"><?php echo $paidProvider->totalItemCount;?></a>
		<span><?php echo Yii::t('order','Paid Invoice');?></span>
	</li>
	<li class="col-xs-3">
		<a class="btn btn-default" href="#unpaid-invoice"><?php echo $unpaidProvider->totalItemCount;?></a>
		<span><?php echo Yii::t('order','Unpaid Invoice');?></span>
	</li>
    */ ?>
	<li class="col-xs-3">
		<a class="btn btn-default" href="#refund-invoice"><?php echo $refundProvider->totalItemCount;?></a>
		<span><?php echo Yii::t('order','Refund Invoice');?></span>
	</li>
	<li class="col-xs-3">
		<a class="btn btn-default" href="#all-invoice"><?php echo $dataProvider->totalItemCount;?></a>
		<span><?php echo Yii::t('order','All Invoice');?></span>
	</li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage');?> <?php echo Yii::t('order','Invoices');?></h4>
	</div>
	<div class="panel-body">
		<?php echo CHtml::link(Yii::t('global','Advanced Search'),'#',array('class'=>'search-button pull-right btn btn-default-alt')); ?>
		<ul class="nav nav-tabs">
			<?php /*<li class="">
				<a data-toggle="tab" href="#paid-invoice">
					<strong><?php echo Yii::t('order','Paid Invoice');?></strong> <span class="badge badge-warning"><?php echo $paidProvider->totalItemCount;?></span>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#unpaid-invoice">
					<strong><?php echo Yii::t('order','Unpaid Invoice');?></strong> <span class="badge badge-warning"><?php echo $unpaidProvider->totalItemCount;?></span>
				</a>
			</li> */ ?>
			<li class="">
				<a data-toggle="tab" href="#refund-invoice">
					<strong><?php echo Yii::t('order','Refund Invoice');?></strong> <span class="badge badge-warning"><?php echo $refundProvider->totalItemCount;?></span>
				</a>
			</li>
			<li class="active">
				<a data-toggle="tab" href="#all-invoice">
					<strong><?php echo Yii::t('order','All Invoice');?></strong> <span class="badge badge-warning"><?php echo $dataProvider->totalItemCount;?></span>
				</a>
			</li>
		</ul>
		<div class="search-form col-sm-12 mar_top3" style="display:block;margin-top: 20px;">
            <div class="row">
                <?php $this->renderPartial('_search',array(
                    'model'=>$dataProvider->model,
                )); ?>
            </div>
		</div><!-- search-form -->
		<div class="tab-content pill-content">
            <?php /*
			<div id="paid-invoice" class="tab-pane">
				<div class="table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$paidProvider,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'paid-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'customer_id',
							'type'=>'raw',
							'value'=>'$data->customer_rel->name'
						),
						array(
							'name'=>'serie',
							'type'=>'raw',
							'value'=>'$data->invoiceFormatedNumber'
						),
						array(
							'name'=>'status',
							'type'=>'raw',
							'value'=>'PLookup::item(\'InvoiceStatus\',$data->status)'
						),
						array(
							'header'=>'price',
							'type'=>'raw',
							'value'=>'number_format($data->totalPrice,0,\',\',\'.\')'
						),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>'date("d-m-Y H:i",strtotime($data->date_entry))'
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{update}{delete}',
							'buttons'=>array
								(
									'update'=>array(
											'label'=>'<i class="fa fa-pencil"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/update",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Update','id'=>'update-list'),
											'visible'=>'true',
										),
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/delete",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Delete','id'=>'delete-list'),
											'visible'=>'true',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>
			</div>
			<div id="unpaid-invoice" class="tab-pane">
				<div class="table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$unpaidProvider,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'unpaid-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'customer_id',
							'type'=>'raw',
							'value'=>'$data->customer_rel->name'
						),
						array(
							'name'=>'serie',
							'type'=>'raw',
							'value'=>'$data->invoiceFormatedNumber'
						),
						array(
							'name'=>'status',
							'type'=>'raw',
							'value'=>'PLookup::item(\'InvoiceStatus\',$data->status)'
						),
						array(
							'header'=>'price',
							'type'=>'raw',
							'value'=>'number_format($data->totalPrice,0,\',\',\'.\')'
						),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>'date("d-m-Y H:i",strtotime($data->date_entry))'
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{update}{delete}',
							'buttons'=>array
								(
									'update'=>array(
											'label'=>'<i class="fa fa-pencil"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/update",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Update','id'=>'update-list'),
											'visible'=>'true',
										),
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/delete",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Delete','id'=>'delete-list'),
											'visible'=>'true',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>
			</div> */ ?>
			<div id="refund-invoice" class="tab-pane">
				<div class="table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$refundProvider,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'refund-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'serie',
							'type'=>'raw',
							'value'=>'$data->invoiceFormatedNumber'
						),
						array(
							'name'=>'status',
							'type'=>'raw',
							'value'=>'PLookup::item(\'InvoiceStatus\',$data->status)'
						),
						array(
							'header'=>Yii::t('order', 'Total Invoice'),
							'type'=>'raw',
							'value'=>'number_format($data->totalPrice,0,\',\',\'.\')'
						),
                        array(
                            'name'=>'customer_rel.name',
                            'type'=>'raw',
                            'value'=>'$data->customer_rel->name'
                        ),
                        array(
                            'name'=>'customer_rel.telephone',
                            'type'=>'raw',
                            'value'=>'$data->customer_rel->telephone'
                        ),
                        array(
                            'name'=>'customer_rel.address',
                            'type'=>'raw',
                            'value'=>'$data->customer_rel->address'
                        ),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>'date("d-m-Y H:i",strtotime($data->date_entry))'
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{update}{delete}',
							'buttons'=>array
								(
									'update'=>array(
											'label'=>'<i class="fa fa-pencil"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/update",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Update','id'=>'update-list'),
											'visible'=>'true',
										),
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/delete",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Delete','id'=>'delete-list'),
											'visible'=>'true',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>
			</div>
			<div id="all-invoice" class="tab-pane active">
				<div class="table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$dataProvider,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'all-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'serie',
							'type'=>'raw',
							'value'=>'$data->invoiceFormatedNumber'
						),
						array(
							'name'=>'status',
							'type'=>'raw',
							'value'=>'PLookup::item(\'InvoiceStatus\',$data->status)'
						),
						array(
							'header'=>Yii::t('order', 'Total Invoice'),
							'type'=>'raw',
							'value'=>'number_format($data->totalPrice,0,\',\',\'.\')'
						),
                        array(
                            'header'=>Yii::t('order', 'Cost'),
                            'type'=>'raw',
                            'value'=>'number_format($data->totalCost,0,\',\',\'.\')'
                        ),
                        array(
                            'header'=>Yii::t('order', 'Net Profit'),
                            'type'=>'raw',
                            'value'=>'number_format($data->netProfit,0,\',\',\'.\')'
                        ),
                        array(
                            'name'=>'customer_rel.name',
                            'type'=>'raw',
                            'value'=>'$data->customer_rel->name'
                        ),
                        array(
                            'name'=>'customer_rel.telephone',
                            'type'=>'raw',
                            'value'=>'$data->customer_rel->telephone'
                        ),
                        array(
                            'name'=>'customer_rel.address',
                            'type'=>'raw',
                            'value'=>'$data->customer_rel->address'
                        ),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>'date("d-m-Y H:i",strtotime($data->date_entry))'
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{update}{delete}',
							'buttons'=>array
								(
									'update'=>array(
											'label'=>'<i class="fa fa-pencil"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/update",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Update','id'=>'update-list'),
											'visible'=>'true',
										),
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/delete",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Delete','id'=>'delete-list'),
											'visible'=>'true',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$('.stats').find('.btn').click(function(){
	$('.nav-tabs').find('a[href="'+$(this).attr('href')+'"]').trigger('click');
	return false;
});
$(function(){
    $('.page-container').toggleClass('hidden-sidebar');
    $('.sidebar').hide();
});
</script>
