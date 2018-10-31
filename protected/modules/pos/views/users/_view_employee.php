<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        array(
			'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		'employee_id',
		'name',
		array(
			'name'=>'employee_job_title',
			'value'=>'$data->employeejobtitle->title',
		),
		array(
			'header'=>'Store ID',
			'value'=>'EmployeeStores::get_store($data->employee_id)',
		),
        array(
			'type'=>'raw',
            'value'=>'CHtml::link(\'select\',\'#\',array(\'onclick\'=>"choose(\'".$data->employee_id."\');",\'id\'=>$data->employee_id))',
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
    ),
));
?>

<script>
function choose(employeeid){
	$('#User_username').val(employeeid);
	return false;
}
</script>
