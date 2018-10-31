<?php
$this->breadcrumbs=array(
	'Promo'=>array('view'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Promo', 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' Promo', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('promo-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage');?> <?php echo Yii::t('menu','Promo');?></h4>
	</div>
	<div class="panel-body">
		<p>
		<?php echo Yii::t('global','You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.');?>
		</p>

		<?php echo CHtml::link(Yii::t('global','Advanced Search'),'#',array('class'=>'search-button')); ?>
		<div class="search-form" style="display:none">
		<?php $this->renderPartial('_search',array(
			'model'=>$model,
		)); ?>
		</div><!-- search-form -->
		<div class="table-responsive">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'params-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'itemsCssClass'=>'table table-striped mb30',
			'afterAjaxUpdate' => 'reloadGrid',
			'columns'=>array(
				array(
					'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
				),
				array(
					'name'=>'code',
					'type'=>'raw',
					'value'=>'$data->code',
				),
				array(
					'name'=>'type',
					'type'=>'raw',
					'value'=>'$data->items($data->type)',
					'filter'=>Promo::items(),
				),
				array(
					'name'=>'value',
					'type'=>'raw',
					'value'=>'$data->value',
				),
				array(
					'name'=>'start_at',
					'type'=>'raw',
					'value'=>'$data->start_at',
				),
				array(
					'name'=>'end_at',
					'type'=>'raw',
					'value'=>'$data->end_at',
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{update}{delete}',
					'buttons'=>array
						(
							'update'=>array(
									'imageUrl'=>false,
									'label'=>'<span class="fa fa-pencil"></span>',
									'options'=>array('title'=>'Update'),
									'visible'=>'Rbac::ruleAccess(\'update_p\')',
								),
							'delete'=>array(
									'imageUrl'=>false,
									'label'=>'<span class="fa fa-trash-o"></span>',
									'options'=>array('title'=>'Delete'),
									'visible'=>'Rbac::ruleAccess(\'delete_p\')',
								),
						),
					'visible'=>Rbac::ruleAccess('update_p'),
					'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
				),
			),
		)); ?>
		</div>
	</div>
</div>
