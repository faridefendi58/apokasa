<?php

/**
 * This is the model class for table "{{product_price}}".
 *
 * The followings are the available columns in table '{{product_price}}':
 * @property integer $id
 * @property integer $product_id
 * @property string $purchase_date
 * @property double $purchase_price
 * @property integer $purchase_stock
 * @property integer $current_stock
 * @property integer $supplier_id
 * @property double $sold_price
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class ProductPrice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{product_price}}';
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db2;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, sold_price, date_entry, user_entry', 'required'),
			array('product_id, purchase_stock, current_stock, supplier_id, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('purchase_price, sold_price', 'numerical'),
			array('purchase_date, date_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, product_id, purchase_date, purchase_price, purchase_stock, current_stock, supplier_id, sold_price, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'purchase_date' => Yii::t('product','Purchase Date'),
			'purchase_price' => Yii::t('product','Purchase Price'),
			'purchase_stock' => Yii::t('product','Purchase Stock'),
			'current_stock' => Yii::t('product','Current Stock'),
			'supplier_id' => 'Supplier',
			'sold_price' => Yii::t('product','Sold Price'),
			'date_entry' => Yii::t('global','Date Entry'),
			'user_entry' => Yii::t('global','User Entry'),
			'date_update' => Yii::t('global','Date Update'),
			'user_update' => Yii::t('global','User Update'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('purchase_date',$this->purchase_date,true);
		$criteria->compare('purchase_price',$this->purchase_price);
		$criteria->compare('purchase_stock',$this->purchase_stock);
		$criteria->compare('current_stock',$this->current_stock);
		$criteria->compare('supplier_id',$this->supplier_id);
		$criteria->compare('sold_price',$this->sold_price);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('user_entry',$this->user_entry);
		$criteria->compare('date_update',$this->date_update,true);
		$criteria->compare('user_update',$this->user_update);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductPrice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
