<h5 class="subtitle"><?php echo $this->title;?></h5>
<ul class="<?php echo $this->itemsCssClass;?>">
<?php foreach($dataProvider->data as $data):?>
	<li>
		<?php echo CHtml::link('<i class="elusive icon-chevron-right"></i>'.$data->title,array('/post/view','id'=>$data->id,'title'=>$data->title));?>
	</li>
<?php endforeach;?>
</ul>
