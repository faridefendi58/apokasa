<div class="mix people col-sm-4" style="display: inline-block;">
	<div class="shadow-wrap">
		<div class="item-thumbs">
			<a class="fancybox hover-wrap" title="<?php echo $data->name;?>" data-fancybox-group="gallery" href="<?php echo Yii::app()->request->baseUrl.'/'.$data->src.$data->image;?>">
				<span class="overlay-img"></span><span class="overlay-img-thumb"><i class="fa fa-plus"></i></span>
			</a>
			<img class="img-responsive" alt="" src="<?php echo Yii::app()->request->baseUrl.'/'.$data->src.$data->image;?>">
		</div>
		<h3><a href="#"><?php echo $data->name;?></a></h3>
		<p><?php echo $data->description;?></p>
	</div>
</div>
