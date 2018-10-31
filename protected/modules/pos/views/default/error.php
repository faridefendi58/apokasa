<?php
$this->pageTitle=Yii::app()->config->get('site_name') . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>
<div class="error-wrapper text-center">
	<h1>Error <?php echo $code; ?></h1>

	<h5>
	<?php echo CHtml::encode($message); ?>
	</h5>
</div>
