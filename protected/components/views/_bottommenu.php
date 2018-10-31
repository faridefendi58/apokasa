<?php
	$this->widget('zii.widgets.CMenu', array(
		'id'=>'bottom-menu',
		'items'=>$items,
		'htmlOptions'=>array('class'=>'nav'),
		'activeCssClass'=>'active',
		'encodeLabel'=>false,
	));
?>
<script type="text/javascript">
$(function(){
	var url='<?php echo Yii::app()->request->url;?>';
	$('ul[id="bottom-menu"]').find('a').each(function(){
		if($(this).attr('href')==url)
			$(this).parent().addClass('active');
	});
	$('.navbar-toggle').click(function(){
		$('#menu-link').toggle();
	});
});
</script>
