<?php
$this->breadcrumbs=array(
	'Rbac Groups'=>array('view'),
	'Priviledge'=>array('priviledge','id'=>$_GET['id']),
	$_GET['id'],
);

$this->menu=array(
	array('label'=>'Create Group', 'url'=>array('create')),
	array('label'=>'Manage Group', 'url'=>array('view')),
);

Yii::app()->clientScript->registerScript('check-action', "
$('#checkall').click(function(){
	if(this.checked == true){
		$('.checklist:not(:checked)').attr('checked',true);
		$('.checkitem:not(:checked)').attr('checked',true);
		$('#btn-submit').removeAttr('disabled');
	}else{
		$('.checklist:checked').attr('checked',false);
		$('.checkitem:checked').attr('checked',false);
		$('#btn-submit').attr('disabled','disabled');
	}
});
$('.checklist').click(function(){
	if(this.checked == true)
		$('#btn-submit').removeAttr('disabled');
	else{
		$('#checkall:checked').attr('checked',false);
		var tot_checked=$('.checklist:checked').length;
		if(tot_checked==0)
			$('#btn-submit').attr('disabled','disabled');
	}
})
");
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title">Priviledge Groups</h4>
	</div>
	<div class="panel-body">
		<?php if(Yii::app()->user->hasFlash('grouprbac')): ?>
		<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('grouprbac'); ?>
		</div>
		<?php endif; ?>

		<div class="table-responsive">
		<?php $form = $this->beginWidget('CActiveForm',array('htmlOptions'=>array('name'=>'userrbac'))); ?>
			<table class="table table-striped mb30">
			<thead>
				<tr>
					<th><?php echo CHtml::checkBox('checkall');?></th>
					<th>Module</th>
					<th>Controller</th>
					<th colspan="4">Priviledge</th>
				</tr>
			</thead>
			<?php 
			foreach($dataProvider as $data){
			$class=($no%2>0)? 'even':'odd';
			$module=$data['module'];
			$controller=$data['controller'];
			$alias=$data['alias'];
			echo '<tr class="'.$class.'">';
				echo '<td style="text-align:center;">'.$form->checkBox($model,'check_list['.$module.']['.$controller.']',array('onclick'=>'chooseAction("'.$module.'","'.$controller.'",4)','id'=>$module.'-'.$controller,'class'=>'checklist')).'</td>';
				echo '<td>'.$module.'</td>';
				echo '<td>'.$alias.'</td>';
				$act=1;
				foreach(Rbac::itemsPriviledge() as $priv_type=>$priv_name){
					echo '<td>';
					if(RbacGroupAccess::isChecked($module,$controller,$_GET['id'],$priv_type))
						echo $form->checkBox($model,'access['.$module.']['.$controller.']['.$priv_type.']',array('id'=>$module.'-'.$controller.'-'.$act,'class'=>'checkitem','checked'=>'checked'));
					else
						echo $form->checkBox($model,'access['.$module.']['.$controller.']['.$priv_type.']',array('id'=>$module.'-'.$controller.'-'.$act,'class'=>'checkitem'));
					echo '<label>'.$priv_name.'</label>';
					echo '</td>';
					$act++;
				}
			echo '</tr>';
			$no++;
			} 
			?>
			</table>
			<br class="clear"/>
			<?php echo CHtml::submitButton(Yii::t('global','Save'),array('style'=>'min-width:100px;','id'=>'btn-submit','disabled'=>'disabled')); ?>
		<?php $this->endWidget(); ?>
		</div>
	</div>
</div>

<script>
	function chooseAction(module,controller,num)
	{
		var isChecked=document.getElementById(module+'-'+controller).checked;
		for(i=1; i<=num; i++){
			if(isChecked)
				document.getElementById(module+'-'+controller+'-'+i).checked=true;
			else
				document.getElementById(module+'-'+controller+'-'+i).checked=false;
		}
	}
	function checkAll(field)
	{
		for (i = 0; i < field.length; i++){
			for(j=0; j<field[i].length; j++){
				field[i][j].checked = true ;
			}
		}
		alert(field.length);
	}
</script>
