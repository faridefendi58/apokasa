<?php if($this->type=='right'):?>
<h5 class="subtitle"><?php echo $this->title;?></h5>
<ul class="<?php echo $this->itemsCssClass;?>">
<?php foreach($dataProvider->data as $data):?>
	<li>
		<?php echo CHtml::link('<i class="elusive icon-chevron-right"></i>'.$data->category_name,array('/post/index','type'=>$data->key));?>
	</li>
<?php endforeach;?>
</ul>
<?php elseif ($this->type=='bottom'):?>
<h3 class="widgettitle"><?php echo $this->title;?></h3>			
	<ul class="posts-list">
		<?php foreach($dataProvider->data as $data):?>
		<li>
			<a href="<?php echo $data->url;?>"><img width="110" height="96" src="<?php echo Yii::app()->request->baseUrl;?>/images/smartmag/6831724767_1ba5d434e5_b-110x96.jpg" class="attachment-post-thumbnail wp-post-image no-display appear" alt="6831724767_1ba5d434e5_b" title="Android Toy Restyled Again for the Latest Phone"></a>
			<div class="content">
				<time datetime="2013-12-16T15:57:52+00:00"><?php echo $data->category_name;?></time>
				<span class="comments"><a href="#comments"><i class="fa fa-comments-o"></i><?php echo $data->post_count;?></a></span>
				<a href="<?php echo $data->url;?>" title=""><?php echo $data->parseContent(7);?></a>
			</div>
		</li>
		<?php endforeach;?>
	</li>
</ul>
<?php endif;?>
