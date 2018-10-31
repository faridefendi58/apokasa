<tr>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'items-form')); ?>
	<td>&nbsp;</td>
	<td>
		<?php echo $form->dropDownList($model,'product_id',Product::items('- Pilih Item -'),array('class'=>'form-control','placeholder'=>'Nama Item')); ?>
				<?php /*$this->widget('CAutoComplete', array(
					'name'=>'title',
					'url'=>array('admin/orders/suggestItems'),
					'multiple'=>false,
					'htmlOptions'=>array('size'=>30,'class'=>'form-control','id'=>'scan','placeholder'=>Yii::t('order','Enter Item Name Or Scan Item')),
					'id'=>uniqid(),
					'mustMatch'=>false,
					'delay'=>1,
					'cacheLength'=>50,
					'max'=>15,
				));*/ ?>
	</td>

	<td>
		<?php echo $form->textField($model,'quantity',array('class'=>'form-control','placeholder'=>'Quantity')); ?>
	</td>

	<td>
		<?php //echo $form->textField($model,'price',array('class'=>'form-control','placeholder'=>'Price','readOnly'=>true)); ?>
	</td>
	<td>
		<?php 
		echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('/admin/invoices/addItems','id'=>$model->invoice_id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$.fn.yiiGridView.update(\'items-grid\', {
							data: $(this).serialize()
						});
						return false;
					}
					return false;
				}'
			),
			array('style'=>'width:100px','id'=>uniqid(),'class'=>'btn btn-success'));
		?>
	</td>
<?php $this->endWidget(); ?>
</tr>
<script type="text/javscript">

</script>
