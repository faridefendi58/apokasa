<?php
$this->breadcrumbs=array(
	'Invoice'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('global','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Invoice', 'url'=>array('view')),
);
?>
<div class="row">
	<div class="col-sm-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">Invoice #<?php echo $model->id; ?></h4>
		</div>
		<div class="panel-body">
			<div class="tabbable">
			<ul class="nav nav-pills nav-justified">
				<li class="active">
					<a data-toggle="tab" href="#detail-invoice">
						<strong>Detail Invoice</strong>
					</a>
				</li>
				<li class="">
					<a data-toggle="tab" href="#update-invoice">
						<strong>Manage Invoice</strong>
					</a>
				</li>
			</ul>
			<div class="tab-content pill-content">
				<div id="detail-invoice" class="tab-pane active">
					<div class="row">
					<div class="table-responsive col-sm-8 no-padding">
					<?php
					$this->widget('zii.widgets.CDetailView', array(
						'data'=>$model,
						'id'=>'detail-invoice',
						'htmlOptions'=>array('class'=>'table table-striped mb30'),
						'attributes'=>array(
							array(
								'name'=>'serie',
								'type'=>'raw',
								'value'=>$model->invoiceFormatedNumber,
							),
							array(
								'name'=>'Total',
								'type'=>'raw',
								'value'=>'Rp. '.number_format($model->totalPrice,2,',','.'),
							),
							array(
								'name'=>'status',
								'type'=>'raw',
								'value'=>PLookup::item('InvoiceStatus',$model->status),
							),
							array(
								'name'=>'Customer',
								'type'=>'raw',
								'value'=>$model->customer_rel->name,
								'visible'=>$model->customer_id>0,
							),
							array(
								'name'=>'Issued At',
								'type'=>'raw',
								'value'=>date("d-m-Y H:i",strtotime($model->date_entry)),
							),
							array(
								'name'=>'Paid At',
								'type'=>'raw',
								'value'=>date("d-m-Y H:i",strtotime($model->date_entry)),
								'visible'=>$model->status==1,
							),
							array(
								'name'=>'notes',
								'type'=>'raw',
								'value'=>$model->notes,
							),
						),
					));
					?>
					</div>
					<div class="col-sm-4">
						<div class="dropdown widget clearfix">
							<ul class="dropdown-menu" style="display: block; position: static;" role="menu">
								<?php if($model->status<3):?>
								<li>
									<a id="btn-refund" href="<?php echo Yii::app()->createUrl(Yii::app()->controller->module->id.'/invoices/refund',array('id'=>$model->id));?>">
										<i class="fa fa-rotate-right"></i>
										Refund
									</a>
								</li>
								<?php endif;?>
								<li>
									<a id="btn-print-preview" href="<?php echo Yii::app()->createUrl(Yii::app()->controller->module->id.'/invoices/printPreview',array('id'=>$model->id));?>">
										<i class="fa fa-print"></i>
										Print Preview
									</a>
								</li>
								<li>
									<a id="btn-delete" href="<?php echo Yii::app()->createUrl(Yii::app()->controller->module->id.'/invoices/delete',array('id'=>$model->id,'ajax'=>true));?>">
										<i class="fa fa-trash-o"></i>
										Delete
									</a>
								</li>
								<li>
									<a id="btn-change" href="<?php echo Yii::app()->createUrl(Yii::app()->controller->module->id.'/orders/change',array('id'=>$model->id));?>">
										<i class="fa fa-pencil"></i>
										Change Invoice
									</a>
								</li>
							</ul>
						</div>
					</div>
					</div>
				</div>
				<div id="update-invoice" class="tab-pane">
					<?php if(Yii::app()->user->hasFlash('update')): ?>
					<div class="alert alert-success">
						<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
						<?php echo Yii::app()->user->getFlash('update'); ?>
					</div>
					<?php endif; ?>

					<?php $form=$this->beginWidget('CActiveForm'); ?>
					<div class="form-group col-sm-4">
						<?php echo $form->label($model,'customer_id',array('class'=>'control-label')); ?>
						<?php $this->widget('ext.bootstrap-select.TbSelect',array(
								   'model' => $model,
								   'attribute' => 'customer_id',
								   'data' => Customer::items(),
								   'htmlOptions' => array(
										//'multiple' => true,
										'data-live-search'=>true,
										'class'=>'form-control no-margin',
								   ),
						)); ?>
					</div>

					<div class="form-group col-sm-4">
						<?php echo $form->label($model,'status',array('class'=>'control-label')); ?>
						<?php echo $form->dropDownList($model,'status',PLookup::items('InvoiceStatus'),array('class'=>'form-control')); ?>
					</div>

					<div class="form-group col-sm-4">
						<?php echo $form->label($model,'date_entry',array('class'=>'control-label')); ?>
						<?php
						$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'model'=>$model, //Model object
							'attribute'=>'date_entry', //attribute name
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>'yy-mm-dd',
								'changeMonth' => 'true',
								'changeYear'=>'true',
								'constrainInput' => 'false'
							),
							'htmlOptions'=>array(
								'class'=>'form-control',
								'value'=>date('Y-m-d',strtotime($model->date_entry)),
							),
						));
						?>
					</div>

					<div class="form-group col-sm-4">
						<?php echo $form->label($model,'paid_at',array('class'=>'control-label')); ?>
						<?php
						$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'model'=>$model, //Model object
							'attribute'=>'paid_at', //attribute name
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>'yy-mm-dd',
								'changeMonth' => 'true',
								'changeYear'=>'true',
								'constrainInput' => 'false'
							),
							'htmlOptions'=>array(
								'class'=>'form-control',
								'value'=>date('Y-m-d',strtotime($model->paid_at)),
							),
						));
						?>
					</div>

					<div class="form-group col-sm-4">
						<?php echo $form->label($model,'serie',array('class'=>'control-label')); ?>
						<div class="row">
							<div class="col-sm-6">
								<?php echo $form->textField($model,'serie',array('class'=>'form-control')); ?>
							</div>
							<div class="col-sm-6">
								<?php echo $form->textField($model,'nr',array('class'=>'form-control col-sm-6')); ?>
							</div>
						</div>
					</div>

					<div class="form-group col-sm-4">
						<?php echo $form->label($model,'notes',array('class'=>'control-label')); ?>
						<?php echo $form->textField($model,'notes',array('class'=>'form-control')); ?>
					</div>

					<div class="form-group col-sm-12">Items Data:</div>
					<div class="table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$itemsProvider,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'items-grid',
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
							'value'=>'$data->price'
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{delete}{add}',
							'buttons'=>array
								(
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/deleteItem",array(\'id\'=>$data->id))',
											'options'=>array('title'=>'Delete','id'=>'delete-list'),
											'visible'=>'true',
										),
									'add'=>array(
											'label'=>'<i class="fa fa-plus"></i>',
											'imageUrl'=>false,
											'url'=>'Yii::app()->createUrl("/".Yii::app()->controller->module->id."/invoices/addItems",array(\'id\'=>$data->invoice_id))',
											'options'=>array('title'=>'Tambah Data','id'=>'add-list'),
											'visible'=>'true',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>

					<div class="form-group col-sm-12">
						<?php echo CHtml::submitButton(Yii::t('global','Update'),array('class'=>'btn btn-success','style'=>'min-width:100px;')); ?>
					</div>

					<?php $this->endWidget(); ?>
				</div>
			</div><!-- tab-content -->
			</div><!-- tabbable -->
		</div>
	</div>
	</div>
	<div class="col-sm-4">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title"><?php echo Yii::t('global','Invoice items');?></h4>
		</div>
		<div class="panel-body">
			<div class="table-responsive">
					<?php if($model->items_count>0):?>
						<table class="table table-striped mb10">
							<thead>
								<tr>
									<th>&nbsp;</th>
									<th><?php echo Yii::t('order','Title');?></th>
									<th><?php echo Yii::t('order','Quantity');?></th>
									<th><?php echo Yii::t('order','Price');?></th>
								</tr>
							</thead>
							<tbody>
						<?php $no=1;?>
						<?php foreach($model->items_rel as $item):?>
							<tr>
								<td><?php echo $no;?></td>	
								<td><?php echo CHtml::link($item->title,array('/'.Yii::app()->controller->module->id.'/orders/update','id'=>$item->rel_id));?></td>	
								<td><?php echo $item->quantity;?></td>
								<td><?php echo number_format($item->price,0,',','.');?></td>		
							</tr>
						<?php $no++;?>
						<?php endforeach;?>
							<tr>
								<td colspan="3"><b>TOTAL</b></td>
								<td><b><?php echo number_format($model->totalPrice,0,',','.');?></b></td>
							</tr>
							</tbody>
						</table>
					<?php endif;?>
			</div>
		</div>
	</div>
	</div>
</div>
<button class="btn btn-primary btn-lg hidden" data-target="#myModal" data-toggle="modal" id="launch-modal"> Launch Modal </button>
<div id="myModal" class="modal fade" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
				<h4 id="myModalLabel" class="modal-title">Modal title</h4>
			</div>
			<div class="modal-body has-padding" id="div-for-preview"> Content goes here... </div>
			<div class="modal-footer">
			<button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
			<button class="btn btn-primary" id="btn-print" type="button">Print</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$('#btn-refund').click(function(){
	var $this=$(this);
	if(confirm('Anda yakin ingin melakukan refund untuk invoice ini ?')){
		$.ajax({
			'beforeSend': function() { Loading.show(); },
			'complete': function() { Loading.hide(); },
			'url':$this.attr('href'),
			'type':'post',
			'dataType':'json',
			'success':function(data){
				if(data.status=='success'){
					window.location.reload(true);
				}
			}
		});
	}
	return false;
});
$('#btn-delete').click(function(){
	var $this=$(this);
	if(confirm('Anda yakin ingin menghapus invoice ini ?')){
		$.ajax({
			'beforeSend': function() { Loading.show(); },
			'complete': function() { Loading.hide(); },
			'url':$this.attr('href'),
			'type':'post',
			'dataType':'json',
			'success':function(data){
				window.location.href="<?php echo Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/invoices/view');?>";
			}
		});
	}
	return false;
});
$('#btn-print-preview').click(function(){
	var $this=$(this);
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url':$this.attr('href'),
		'type':'post',
		'dataType':'json',
		'success':function(data){
			if(data.status=='success'){
				$('.modal-content .modal-header').hide();
				$('.modal-content #div-for-preview').html(data.div);
				$('#launch-modal').trigger('click');
			}
		}
	});
	return false;
});
$('a[id="add-list"]').click(function(){
	var $this=$(this);
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url':$this.attr('href'),
		'type':'post',
		'dataType':'json',
		'success':function(data){
			if(data.status=='success'){
				$('#items-grid').find('tbody').append(data.div);
			}
		}
	});
	return false;
});
</script>
