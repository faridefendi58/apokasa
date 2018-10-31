<?php if (Yii::app()->theme->name=='classic'):?>
<?php
	$this->widget('zii.widgets.CMenu', array(
		'id'=>'menu-main-menu',
		'items'=>$items,
		'htmlOptions'=>array('class'=>'jetmenu'),
		'submenuHtmlOptions'=>array('class'=>'dropdown','role'=>'menu'),
		'activeCssClass'=>'active',
		'encodeLabel'=>false,
	));
?>
<script type="text/javascript">
$(function(){
	var url='<?php echo Yii::app()->request->url;?>';
	$('ul[id="menu-main-menu"]').find('a').each(function(){
		if($(this).attr('href')==url)
			$(this).parent().addClass('active');
	});
	$('.navbar-toggle').click(function(){
		$('#menu-link').toggle();
	});
});
</script>
<?php elseif (Yii::app()->theme->name=='jagungbakar'):?>
<nav id="nav_menu">
<?php
	$this->widget('zii.widgets.CMenu', array(
		'id'=>'menu-main-menu',
		'items'=>$items,
		'submenuHtmlOptions'=>array('class'=>'dropdown','role'=>'menu'),
		'activeCssClass'=>'active',
		'encodeLabel'=>false,
	));
?>
</nav>
<?php endif;?>
