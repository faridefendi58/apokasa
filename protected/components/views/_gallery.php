<div id="portfolio-container">
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'__gallery',
		'template'=>"{items}\n{pager}",
	)); ?>
</div>
<script type="text/javascript">
$(function(){
	var img = new Array()
	$('#portfolio-container').find('.img-responsive').each(function(i){
		img.push($(this).height());
	});
	var img_min_height = Math.max.apply(Math, img)+5;
	$('#portfolio-container').find('.img-responsive').attr('style','min-height:'+img_min_height+'px');
});
</script>
