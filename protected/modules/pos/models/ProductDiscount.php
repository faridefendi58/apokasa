<?php

/**
 * This is the model class for table "{{product_discount}}".
 *
 * The followings are the available columns in table '{{product_discount}}':
 * @property integer $id
 * @property integer $product_id
 * @property integer $customer_group_id
 * @property integer $quantity
 * @property string $price
 * @property string $date_start
 * @property string $date_end
 */
class ProductDiscount extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductDiscount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{product_discount}}';
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
			array('product_id', 'required'),
			array('product_id, customer_group_id, quantity', 'numerical', 'integerOnly'=>true),
			array('price', 'length', 'max'=>15),
			array('date_start, date_end, quantity_max, base_price', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, customer_group_id, quantity, price, date_start, date_end', 'safe', 'on'=>'search'),
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
			'product_rel'=>array(self::BELONGS_TO,'Product','product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Item',
			'customer_group_id' => Yii::t('product', 'Customer Group'),
			'quantity' => Yii::t('product', 'Quantity'),
			'price' => Yii::t('product', 'Price'),
			'date_start' => Yii::t('product', 'Date Start'),
			'date_end' => Yii::t('product', 'Date End'),
			'base_price' => Yii::t('product', 'Base Price'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('customer_group_id',$this->customer_group_id);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('date_end',$this->date_end,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
