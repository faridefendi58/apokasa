<?php
$this->breadcrumbs=array(
	'User'=>array('view'),
	'Priviledge'=>array('priviledge','id'=>$_GET['id']),
	$_GET['id'],
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' User', 'url'=>array('view'),'visible'=>Rbac::ruleAccess('read_p')),
	array('label'=>Yii::t('global','Create').' User', 'url'=>array('create'),'visible'=>Rbac::ruleAccess('create_p')),
);

Yii::app()->clientScript->registerScript('search', "
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
});
$('.checkitem').click(function(){
	var tot_checked=$('.checkitem:checked').length;
	if(tot_checked==0)
		$('#btn-submit').attr('disabled','disabled');
	else
		$('#btn-submit').removeAttr('disabled');
});
$(document).ready(function(){
	var group=".$group.";
	var tot_checked=$('.checkitem:checked').length;
	if((group > 0) || (tot_checked>0))
		$('#btn-submit').removeAttr('disabled');
});
var num=$('.checkitem:checked').length;
if(num>0)
	$('#btn-submit').removeAttr('disabled');
");
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">Hak Akses User - <?php echo strtoupper($user->username);?></h4>
	</div>
	<div class="panel-body">
		<?php if(Yii::app()->user->hasFlash('userrbac')): ?>
		<div class="flash-success">
			<?php 
				header('refresh: 3;');
				echo Yii::app()->user->getFlash('userrbac'); 
			?>
		</div>
		<?php endif; ?>

		<div class="table-responsive">
		<?php $form = $this->beginWidget('CActiveForm',array('htmlOptions'=>array('name'=>'userrbac'))); ?>
			<table class="table table-striped mb30">
			<thead>
			<tr>
				<th><center><?php echo CHtml::checkBox('checkall');?></center></th>
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
					if(!empty($group)){  //default priv to group
						if(RbacGroupAccess::isChecked($module,$controller,$group,$priv_type))
							echo $form->checkBox($model,'access['.$module.']['.$controller.']['.$priv_type.']',array('id'=>$module.'-'.$controller.'-'.$act,'class'=>'checkitem','checked'=>'checked'));
						else
							echo $form->checkBox($model,'access['.$module.']['.$controller.']['.$priv_type.']',array('id'=>$module.'-'.$controller.'-'.$act,'class'=>'checkitem'));
					}else{
						if(RbacUserAccess::isChecked($module,$controller,$_GET['id'],$priv_type))
							echo $form->checkBox($model,'access['.$module.']['.$controller.']['.$priv_type.']',array('id'=>$module.'-'.$controller.'-'.$act,'class'=>'checkitem','checked'=>'checked'));
						else
							echo $form->checkBox($model,'access['.$module.']['.$controller.']['.$priv_type.']',array('id'=>$module.'-'.$controller.'-'.$act,'class'=>'checkitem'));
					}			
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
			<?php echo CHtml::submitButton(Yii::t('global','Save'),array('style'=>'min-width:100px;','id'=>'btn-submit','disabled'=>'disabled','class'=>'tombol simpan')); ?>
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
</script>
