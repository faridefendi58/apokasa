<?php if($this->type=='latest'):?>
	<?php if($this->themes=='jagungbakar'):?>
		<div class="row">
		<?php foreach($dataProvider->data as $data):?>	
			<div class="col-md-4">
				<div class="hb-info" data-animated="0">
						<img src="images/blog/1/1.jpg" class="img-responsive" alt=""/>
						<div class="hb-inner">
							<div class="hb-meta">
								<i class="fa fa-comments"></i>
								<span>20 comments</span>
							</div>
							<h4><a href="#"><?php echo CHtml::link($data->content_rel->title,$data->url);?></a></h4>
							<div class="sep"></div>
							<p><?php echo $data->parseContent2(10,false);?></p>
						</div>
				</div>
			</div>
		<?php endforeach;?>
		</div>
	<?php endif;?>
<?php else:?>
<ul class="tab-posts posts-list" id="<?php echo $this->id;?>">
	<?php foreach($dataProvider->data as $data):?>
	<li>
		<?php if($this->themes=='pressa'):?>
		<a href="<?php echo $data->url;?>"><img width="110" height="96" src="<?php echo Yii::app()->request->baseUrl;?>/images/smartmag/6831724767_1ba5d434e5_b-110x96.jpg" class="attachment-post-thumbnail wp-post-image no-display appear" alt="6831724767_1ba5d434e5_b" title="Android Toy Restyled Again for the Latest Phone"></a>
		<div class="content">
			<time datetime="2013-12-16T15:57:52+00:00"><?php echo date("M d, Y",$data->create_time);?> </time>
			<span class="comments"><a href="#comments"><i class="fa fa-comments-o"></i><?php echo $data->commentCount;?></a></span>
			<a href="<?php echo $data->url;?>" title="Android Toy Restyled Again for the Latest Phone"><?php echo $data->parseContent2(7);?></a>
		</div>
		<?php elseif($this->themes=='realto'):?>
			<li><i class="icon-caret-right"></i><?php echo CHtml::link($data->title,$data->url);?></li>
		<?php endif;?>
	</li>
	<?php endforeach;?>
</ul>
<?php endif;?>
<?php
Yii::app()->clientScript->registerScript('js', "
$('.tabs-data').find('ul:first-child').addClass('active');
");
?>
