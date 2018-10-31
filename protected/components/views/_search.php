<?php echo CHtml::textField('question','',array('placeholder'=>Yii::t('global',$this->placeholder), 'id'=>'cari', 'class'=>$this->class_name));?>
<?php 
	Yii::app()->clientScript->registerScript('tekan',"
		$('#cari').keypress(function(e){
			if (e.which == 13) {
				pushSearch(this.value,'".Yii::app()->createUrl('site/search')."','".$this->destination."');
			}
		});
		$('#cari').parent().parent().find('input[type=\"submit\"]').click(function(){
			pushSearch($('#cari').val(),'".Yii::app()->createUrl('site/search')."','".$this->destination."');
		});
	");
?>

<script type="text/javascript">
function pushSearch(question,action,destination)
{	
	$.ajax({
	'beforeSend': function() { Loading.show(); },
	'complete': function() { Loading.hide(); },
      	'url': action,
	'type':'post',
      	'dataType': 'json',
	'data':{"question":question},
      	'success': function(data){
			if(data.status=='success')
				$(destination).html(data.div);
      		},
    	});
    return false;
}
function checkSearch(data,action,destination)
{
	if(data.value!==''){
		pushSearch(data.value,action,destination);	
	}	
}
</script>
