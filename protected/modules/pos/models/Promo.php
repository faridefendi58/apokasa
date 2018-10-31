<?php

/**
 * This is the model class for table "{{promo}}".
 *
 * The followings are the available columns in table '{{promo}}':
 * @property string $id
 * @property string $code
 * @property string $description
 * @property string $type
 * @property string $value
 * @property integer $maxuses
 * @property integer $used
 * @property integer $once_per_client
 * @property integer $active
 * @property string $products
 * @property string $start_at
 * @property string $end_at
 * @property string $date_entry
 * @property string $user_entry
 * @property integer $date_update
 * @property integer $user_update
 */
class Promo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{promo}}';
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
			array('date_entry, user_entry', 'required'),
			array('maxuses, used, once_per_client, active, user_update', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>100),
			array('type', 'length', 'max'=>30),
			array('value', 'length', 'max'=>18),
			array('description, products, date_update, start_at, end_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, description, type, value, maxuses, used, once_per_client, active, products, start_at, end_at, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'description' => 'Description',
			'type' => 'Type',
			'value' => 'Value',
			'maxuses' => 'Maxuses',
			'used' => 'Used',
			'once_per_client' => 'Once Per Client',
			'active' => 'Active',
			'products' => 'Products',
			'start_at' => 'Start At',
			'end_at' => 'End At',
			'date_entry' => 'Date Entry',
			'user_entry' => 'User Entry',
			'date_update' => 'Date Update',
			'user_update' => 'User Update',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('maxuses',$this->maxuses);
		$criteria->compare('used',$this->used);
		$criteria->compare('once_per_client',$this->once_per_client);
		$criteria->compare('active',$this->active);
		$criteria->compare('products',$this->products,true);
		$criteria->compare('start_at',$this->start_at,true);
		$criteria->compare('end_at',$this->end_at,true);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('user_entry',$this->user_entry,true);
		$criteria->compare('date_update',$this->date_update);
		$criteria->compare('user_update',$this->user_update);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Promo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getDiscountValue($id,$price)
	{
		$model=self::model()->findByPk($id);
		if($model->type=='percentage')
			$discount=$price*$model->value/100;
		else
			$discount=$price-$model->value;
		return $discount;
	}

	public function items($type=null)
	{
		
		$items=array('absolute'=>'Absolute','percentage'=>'Percentage');	
		if(!empty($type))
			return $items[$type];
		else
			return $items;
	}
}
