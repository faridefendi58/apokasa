<?php

/**
 * This is the model class for table "{{invoice_item}}".
 *
 * The followings are the available columns in table '{{invoice_item}}':
 * @property string $id
 * @property string $invoice_id
 * @property string $type
 * @property string $rel_id
 * @property string $title
 * @property string $quantity
 * @property double $price
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class InvoiceItem extends CActiveRecord
{
	public $product_id;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{invoice_item}}';
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
			array('user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('invoice_id, quantity', 'length', 'max'=>20),
			array('type', 'length', 'max'=>100),
			array('title', 'length', 'max'=>255),
			array('rel_id, date_update, product_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, invoice_id, type, rel_id, title, quantity, price, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'order_rel'=>array(self::BELONGS_TO,'Order','rel_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'invoice_id' => 'Invoice',
			'type' => 'Type',
			'rel_id' => 'Order Id',
			'title' => 'Title',
			'quantity' => 'Quantity',
			'price' => 'Price',
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
		$criteria->compare('invoice_id',$this->invoice_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('rel_id',$this->rel_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('price',$this->price);
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
	 * @return InvoiceItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/** @params, id = invoice_item_id, type = netto, price, discount */
	public function getOrderPrice($id=0,$type='netto')
	{
		if($id==0)
			$model=$this;
		else
			$model=self::model()->findByPk($id);
		$price=$model->order_rel->price;
		$discount=$model->order_rel->discount;
		switch ($type) {
			case 'netto':
				$p=$price-$discount;
			break;
			case 'price':
				$p=$price;
			break;
			case 'discount':
				$p=$discount;
			break;
		}
		return $p;
	}
}
