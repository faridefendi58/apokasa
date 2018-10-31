<?php
$this->breadcrumbs=array(
	'Params'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('global','Update'),
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
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage');?> Order</h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#detail-order">
					<strong>Detail Order</strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#update-order">
					<strong>Manage Order</strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#invoice-order">
					<strong>Invoice</strong>
				</a>
			</li>
		</ul>
		<div class="tab-content pill-content">
			<div id="detail-order" class="tab-pane active">
				<div class="table-responsive">
				<?php
				$this->widget('zii.widgets.CDetailView', array(
					'data'=>$model,
					'htmlOptions'=>array('class'=>'table table-striped mb30'),
					'attributes'=>array(
						array(
							'name'=>'id',
							'type'=>'raw',
							'value'=>$model->id,
						),
						array(
							'name'=>'product_id',
							'type'=>'raw',
							'value'=>$model->product_rel->name,
						),
						array(
							'name'=>'customer_id',
							'type'=>'raw',
							'value'=>$model->customer_rel->name,
						),
						array(
							'name'=>'quantity',
							'type'=>'raw',
							'value'=>$model->quantity,
						),
						array(
							'name'=>'price',
							'type'=>'raw',
							'value'=>number_format($model->price,2,',','.'),
						),
						array(
							'name'=>'discount',
							'type'=>'raw',
							'value'=>number_format($model->discount,2,',','.'),
						),
						array(
							'name'=>'type',
							'type'=>'raw',
							'value'=>PLookup::item('OrderType',$model->type),
						),
						array(
							'name'=>'status',
							'type'=>'raw',
							'value'=>PLookup::item('OrderStatus',$model->status),
						),
						array(
							'name'=>'Created At',
							'type'=>'raw',
							'value'=>date("d-m-Y H:i",strtotime($model->date_entry)),
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
			</div><!-- detail-invoice -->
			<div id="update-order" class="tab-pane">
				<?php if(Yii::app()->user->hasFlash('update')): ?>
				<div class="alert alert-success">
					<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
					<?php echo Yii::app()->user->getFlash('update'); ?>
				</div>
				<?php endif; ?>

				<?php $form=$this->beginWidget('CActiveForm'); ?>
				<div class="form-group col-sm-4">
					<?php echo $form->label($model,'product_id',array('class'=>'control-label')); ?>
					<?php $this->widget('ext.bootstrap-select.TbSelect',array(
							   'model' => $model,
							   'attribute' => 'product_id',
							   'data' => Product::items(),
							   'htmlOptions' => array(
									//'multiple' => true,
									'data-live-search'=>true,
									'class'=>'form-control no-margin',
							   ),
					)); ?>
				</div>

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
					<?php echo $form->label($model,'quantity',array('class'=>'control-label')); ?>
					<?php echo $form->textField($model,'quantity',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
				</div>

				<div class="form-group col-sm-4">
					<?php echo $form->label($model,'price',array('class'=>'control-label')); ?>
					<?php echo $form->textField($model,'price',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
				</div>

				<div class="form-group col-sm-4">
					<?php echo $form->label($model,'discount',array('class'=>'control-label')); ?>
					<?php echo $form->textField($model,'discount',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
				</div>

				<div class="form-group col-sm-4">
					<?php echo $form->label($model,'status',array('class'=>'control-label')); ?>
					<?php echo $form->dropDownList($model,'status',PLookup::items('OrderStatus'),array('class'=>'form-control')); ?>
				</div>
		
				<div class="form-group col-sm-8">
					<?php echo $form->label($model,'notes',array('class'=>'control-label')); ?>
					<?php echo $form->textField($model,'notes',array('class'=>'form-control')); ?>
				</div>

				<div class="form-group col-sm-12">
					<?php echo CHtml::submitButton(Yii::t('global','Update'),array('class'=>'btn btn-success')); ?>
				</div>

				<?php $this->endWidget(); ?>
			</div><!-- update-order -->
			<div id="invoice-order" class="tab-pane">
				<div class="table-responsive">
				<?php
				$this->widget('zii.widgets.CDetailView', array(
					'data'=>$model->invoice_rel,
					'htmlOptions'=>array('class'=>'table table-striped mb30'),
					'attributes'=>array(
						array(
							'name'=>'serie',
							'type'=>'raw',
							'value'=>CHtml::link($model->invoice_rel->invoiceFormatedNumber,array('/'.Yii::app()->controller->module->id.'/invoices/update','id'=>$model->invoice_id)),
						),
						array(
							'name'=>'Total',
							'type'=>'raw',
							'value'=>'Rp. '.number_format($model->invoice_rel->totalPrice,2,',','.'),
						),
						array(
							'name'=>'status',
							'type'=>'raw',
							'value'=>PLookup::item('InvoiceStatus',$model->invoice_rel->status),
						),
						array(
							'name'=>'Customer',
							'type'=>'raw',
							'value'=>$model->invoice_rel->customer_rel->name,
							'visible'=>$model->invoice_rel->customer_id>0,
						),
						array(
							'name'=>'Issued At',
							'type'=>'raw',
							'value'=>date("d-m-Y H:i",strtotime($model->invoice_rel->date_entry)),
						),
						array(
							'name'=>'Paid At',
							'type'=>'raw',
							'value'=>date("d-m-Y H:i",strtotime($model->invoice_rel->date_entry)),
							'visible'=>$model->invoice_rel->status==1,
						),
						array(
							'name'=>'notes',
							'type'=>'raw',
							'value'=>$model->invoice_rel->notes,
						),
					),
				));
				?>
				</div>
			</div><!-- invoice-order -->
		</div><!-- tab-content -->
	</div><!-- panel-body -->
</div>
