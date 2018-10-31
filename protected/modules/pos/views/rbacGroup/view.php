<?php
$this->breadcrumbs=array(
	'Rbac Groups'=>array('view'),
	Yii::t('global','List'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Group', 'url'=>array('view'),'visible'=>Rbac::ruleAccess('read_p')),
	array('label'=>Yii::t('global','Create').' Group', 'url'=>array('create'),'visible'=>Rbac::ruleAccess('create_p')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('rbac-group-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','List');?> Group</h4>
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
			'id'=>'rbac-group-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'itemsCssClass'=>'table table-striped mb30',
			'columns'=>array(
				array(
					'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
				),
				array(
					'name'=>'group_name',
					'type'=>'raw',
					'value'=>'CHtml::link($data->group_name,array(\'rbacGroup/priviledge/id/\'.$data->id))',
				),
				'level',
				array(
					'class'=>'CButtonColumn',
					'template'=>'{update}{delete}{priviledge}',
					'buttons'=>array
						(
							'update'=>array(
									'visible'=>'Rbac::ruleAccess(\'update_p\')',
								),
							'delete'=>array(
									'visible'=>'Rbac::ruleAccess(\'delete_p\')',
								),
							'priviledge' => array
								(
									'imageUrl'=>Yii::app()->request->baseUrl.'/images/icons/access.png',
									'label'=>'Priviledge',
									'url'=>'Yii::app()->createUrl("appadmin/rbacGroup/priviledge", array("id"=>$data->id))',
									'visible'=>'Rbac::ruleAccess(\'delete_p\')',
								),
						),
					'visible'=>Rbac::ruleAccess('update_p'),
				),
			),
		)); ?>
		</div>
	</div>
</div>
