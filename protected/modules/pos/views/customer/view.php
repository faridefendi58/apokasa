<?php echo $this->renderPartial('search',array('model'=>$dataProvider->model));?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'id'=>'grid-customer',
	'cssFile'=>Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name.'/grid-view.css',
	'columns'=>array(
        	array(
			'name'=>'name',
			'type'=>'raw',
			'value'=>'CHtml::link($data->name,"javascript:void(0);",array(\'id\'=>$data->id,\'class\'=>\'customer-name\',\'onclick\'=>\'choose(this);\'))',
		),
		'telephone',
		array(
			'name'=>'address',
			'value'=>'$data->address',
		),
    	),
));
?>
<script type="text/javascript">
function choose(data)
{
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
	      	'url': "<?php echo Yii::app()->createUrl('/customer/choose');?>",
		'type':'post',
	      	'dataType': 'json',
		'data':{'id':data.id},
	      	'success': function(data){
				if(data.status=='success'){
					$('#dialogItems').dialog('close');
				}
	      		},
	});
	return false;	
}
</script>
