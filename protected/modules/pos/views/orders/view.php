<?php
$this->breadcrumbs=array(
	Yii::t('order','Orders')=>array('view'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array(
		'label'=>Yii::t('global','List').' '.Yii::t('order','Orders'), 
		'url'=>array('view'),
		'visible'=>RbacUserAccess::isChecked(Yii::app()->controller->module->id,'orders',Yii::app()->user->id,'read_p')
	),
	array(
		'label'=>Yii::t('global','Create').' '.Yii::t('order','Orders'), 
		'url'=>array('create'),
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
	$.fn.yiiGridView.update('product-grid', {
		data: $(this).serialize()
	});
	$.fn.yiiGridView.update('customer-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<ul class="row stats">
	<li class="col-xs-3">
		<a class="btn btn-default" href="#all-list">
            <?php echo $productTotal['quantity'];?>
        </a>
		<span style="margin-top: 10px;float: left;"><?php echo Yii::t('order','Order on this month');?></span>
	</li>
    <li class="col-xs-4">
        <a class="btn btn-default" href="#product-order">
            <?php echo number_format($productTotal['tot_price'], 0, ',', '.');?>
        </a>
        <span style="margin-top: 10px;float: left;">
            <?php echo Yii::t('order','Bruto Income On This Month');?>
        </span>
    </li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage');?> <?php echo Yii::t('order','Order');?></h4>
	</div>
	<div class="panel-body">
		<?php echo CHtml::link(Yii::t('global','Advanced Search'),'#',array('class'=>'search-button pull-right btn btn-default-alt')); ?>
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#all-list">
					<strong><?php echo Yii::t('order','All Order');?></strong>
				</a>
			</li>
            <li class="">
                <a data-toggle="tab" href="#product-order">
                    <strong><?php echo Yii::t('order','Product Order');?></strong>
                </a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#customer-order">
                    <strong><?php echo Yii::t('order','Customer Order');?></strong>
                </a>
            </li>
		</ul>
		<div class="search-form  col-sm-12 mar_top2" style="display:block;margin-top: 20px;">
		<?php $this->renderPartial('_search',array(
			'model'=>$dataProvider->model,
		)); ?>
		</div><!-- search-form -->
		<div class="tab-content pill-content">
			<div id="all-list" class="tab-pane active">
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
							'name'=>'title',
							'type'=>'raw',
							'value'=>'$data->title'
						),
						array(
							'name'=>'quantity',
							'type'=>'raw',
							'value'=>'$data->quantity'
						),
						array(
							'name'=>'price',
							'type'=>'raw',
							'value'=>'number_format($data->price,0,\',\',\'.\')'
						),
						/*array(
							'name'=>'discount',
							'type'=>'raw',
							'value'=>'number_format($data->discount,0,\',\',\'.\')'
						),*/
                        array(
                            'header' => Yii::t('order', 'Customer Name'),
                            'type' => 'raw',
                            'value' => '$data->customer_rel->name'
                        ),
                        array(
                            'header' => Yii::t('order', 'Customer Phone'),
                            'type' => 'raw',
                            'value' => '$data->customer_rel->telephone'
                        ),
                        array(
                            'header' => Yii::t('order', 'Customer Address'),
                            'type' => 'raw',
                            'value' => '$data->customer_rel->address'
                        ),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>'date("d-m-Y H:i",strtotime($data->date_entry))'
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{update}{invoice}{delete}',
							'buttons'=>array
								(
									'update'=>array(
											'label'=>'<i class="fa fa-pencil"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/orders/update",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Update','id'=>'update-list'),
											'visible'=>'true',
										),
									'invoice'=>array(
											'label'=>'<i class="fa fa-file-text-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/update",array(\'id\'=>$data->invoice_id))',
											'options'=>array('title'=>'Invoice','id'=>'invoice-list'),
											'visible'=>'true',
										),
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/orders/delete",array(\'id\'=>$data->id))',
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
            <div id="product-order" class="tab-pane">
                <div class="table-responsive">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $productProvider,
                        'itemsCssClass' => 'table table-striped mb30',
                        'id' => 'product-grid',
                        'afterAjaxUpdate' => 'reloadGrid',
                        'columns' => array(
                            array(
                                'value' => '$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
                            ),
                            array(
                                'name' => 'title',
                                'header' => Yii::t('order', 'Product Name'),
                                'type' => 'raw',
                                'value' => $data['title'],
                                'footer' => 'TOTAL',
                                'footerHtmlOptions' => array('style'=>'text-align:center;font-weight:bold;'),
                            ),
                            array(
                                'name' => 'quantity',
                                'header' => Yii::t('order', 'Quantity'),
                                'type' => 'raw',
                                'value' => $data['quantity'],
                                'footer' => $productTotal['quantity'],
                                'htmlOptions' => array('style'=>'text-align:center;'),
                                'footerHtmlOptions' => array('style'=>'text-align:center;font-weight:bold;'),
                            ),
                            array(
                                'name' => 'tot_price',
                                'header' => Yii::t('order', 'Bruto Income'),
                                'type' => 'raw',
                                'value' => 'number_format($data[\'tot_price\'], 0, \',\', \'.\')',
                                'footer' => number_format($productTotal['tot_price'], 0, ',', '.'),
                                'htmlOptions' => array('style'=>'text-align:right;'),
                                'footerHtmlOptions' => array('style'=>'text-align:right;font-weight:bold;'),
                            ),
                            array(
                                'name' => 'tot_cost',
                                'header' => Yii::t('order', 'Total Cost'),
                                'type' => 'raw',
                                'value' => 'number_format($data[\'tot_cost\'], 0, \',\', \'.\')',
                                'footer' => number_format($productTotal['tot_cost'], 0, ',', '.'),
                                'htmlOptions' => array('style'=>'text-align:right;'),
                                'footerHtmlOptions' => array('style'=>'text-align:right;font-weight:bold;'),
                            ),
                            array(
                                'name' => 'net_income',
                                'header' => Yii::t('order', 'Net Income'),
                                'type' => 'raw',
                                'value' => 'number_format($data[\'net_income\'], 0, \',\', \'.\')',
                                'footer' => number_format($productTotal['net_income'], 0, ',', '.'),
                                'htmlOptions' => array('style'=>'text-align:right;'),
                                'footerHtmlOptions' => array('style'=>'text-align:right;font-weight:bold;'),
                            ),
                            array(
                                'name' => 'average_cost_price',
                                'header' => Yii::t('order', 'Average Cost Price'),
                                'type' => 'raw',
                                'value' => 'number_format($data[\'average_cost_price\'], 0, \',\', \'.\')',
                                'footer' => number_format($productTotal['average_cost_price'], 0, ',', '.'),
                                'htmlOptions' => array('style'=>'text-align:right;'),
                                'footerHtmlOptions' => array('style'=>'text-align:right;font-weight:bold;'),
                            )
                        ),
                    )); ?>
                </div>
            </div>
            <!-- customer order -->
            <div id="customer-order" class="tab-pane">
                <div class="table-responsive">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $customerProvider,
                        'itemsCssClass' => 'table table-striped mb30',
                        'id' => 'customer-grid',
                        'afterAjaxUpdate' => 'reloadGrid',
                        'columns' => array(
                            array(
                                'value' => '$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
                            ),
                            array(
                                'name' => 'customer_name',
                                'header' => Yii::t('order', 'Customer Name'),
                                'type' => 'raw',
                                'value' => $data['customer_name'],
                            ),
                            array(
                                'name' => 'customer_address',
                                'header' => Yii::t('order', 'Customer Address'),
                                'type' => 'raw',
                                'value' => $data['customer_address'],
                            ),
                            array(
                                'name' => 'total_quantity',
                                'header' => Yii::t('order', 'Sum Of Product Order'),
                                'type' => 'raw',
                                'value' => $data['total_quantity'],
                                'htmlOptions' => array('style' => 'text-align:center;')
                            ),
                            array(
                                'name' => 'total_price',
                                'header' => Yii::t('order', 'Amount Of Order'),
                                'type' => 'raw',
                                'value' => 'number_format($data[\'total_price\'], 0, \',\', \'.\')',
                                'htmlOptions' => array('style' => 'text-align:right;')
                            ),
                            array(
                                'class' => 'CButtonColumn',
                                'template' => '{update}',
                                'buttons' => array
                                (
                                    'update' => array(
                                        'label' => '<i class="fa fa-money"></i>',
                                        'imageUrl' => false,
                                        'url' => 'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/update",array(\'id\'=>$data[\'invoice_id\']))',
                                        'options' => array('title'=> Yii::t('order', 'Invoice'),'id'=>'update-list'),
                                        'visible' => 'true',
                                    ),
                                ),
                                'htmlOptions' => array('style'=>'width:10%;','class'=>'table-action'),
                            ),
                        ),
                    )); ?>
                </div>
            </div>
            <!-- endof customer order -->
		</div>
	</div>
</div>
<script type="text/javascript">
$('.stats').find('.btn').click(function(){
	$('.nav-tabs').find('a[href="'+$(this).attr('href')+'"]').trigger('click');
	return false;
});
</script>
