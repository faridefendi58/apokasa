<?php foreach(Lookup::items('ProductType') as $id_type=>$type):?>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-3">
				<h3><?php echo $type;?></h3>
				<div class="bottom-teaser-small-left"></div>
			</div>
			<div class="col-sm-9">
				<ul id="collection-menu" class="jetmenu">
					<?php foreach(Product::items($id_type) as $product_id=>$product_name):?>
						<li class="">
							<?php $items=ProductItems::items($product_id);?>
							<?php echo CHtml::link($product_name,array('product/detail','id'=>$product_id,'title'=>$product_name),array('id'=>'link-product'));?>
							<?php if(count($items)>0 && ProductItems::hasChild($product_id)):?>
							<ul class="dropdown" style="display: none;">
								<?php foreach($items as $product_item_id=>$item_name):?>
								<?php $items2=ProductItems::items($product_item_id,50,null,1);?>
								<li><!-- diilangi -->
									<?php echo CHtml::link($item_name,array('product/view','id'=>$product_item_id,'title'=>$item_name),array('id'=>'link-product'));?>
								</li>
								<?php endforeach;?>
							</ul>
							<?php endif;?>
						</li>
					<?php endforeach;?>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="clearfix padding20" id="type-<?php echo $id_type;?>">
			<?php if($id_type==1):?>
				<?php $pitems=ProductItems::getDataProviderByType($id_type,4,1);?>
			<?php else:?>
				<?php $pitems=ProductItems::getDataProviderByType($id_type,4);?>
			<?php endif;?>
			<?php foreach($pitems->data as $data):?>
			<div class="mix people col-sm-3" style="display: inline-block;">
				<div class="shadow-wrap">
					<div class="item-thumbs">
						<a class="fancybox hover-wrap" title="<?php echo $data->name;?>" data-fancybox-group="gallery" href="<?php echo Yii::app()->request->baseUrl.'/'.$data->image_one_rel->src.$data->image_one_rel->image;?>">
							<span class="overlay-img"></span><span class="overlay-img-thumb"><i class="fa fa-plus"></i></span>
						</a>
						<img class="img-responsive" alt="" src="<?php echo Yii::app()->request->baseUrl.'/'.$data->image_one_rel->thumb.$data->image_one_rel->image;?>">
					</div>
					<a href="#"><?php echo $data->name;?></a>
				</div>
			</div>
			<?php endforeach;?>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="clearfix padding20 text-right">
		<?php echo CHtml::link('BEST DEAL',array('/best-deal'),array('class'=>'btn btn-green','style'=>'min-width:200px;padding:8px 20px;'));?>
		<?php echo CHtml::link('DOWNLOAD CATALOG',Catalogue::getUrl($id_type),array('class'=>'btn btn-green','style'=>'min-width:200px;padding:8px 20px;'));?>
		<hr/>
		</div>
	</div>
<?php endforeach;?>
<script type="text/javascript">
$('a[id="link-product"]').click(function(){
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url': $(this).attr('href'),
		'type':'post',
		'dataType':'json',
		'success': function(data){
			if(data.status=='success'){
				$('#type-'+data.type).html(data.div);
			}
		},
	});
	return false;
});
</script>
