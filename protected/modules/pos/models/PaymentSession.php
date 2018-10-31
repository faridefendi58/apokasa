<?php

/**
 * This is the model class for table "{{payment_session}}".
 *
 * The followings are the available columns in table '{{payment_session}}':
 * @property integer $id
 * @property integer $name
 * @property double $equity
 * @property string $date_entry
 * @property integer $user_entry
 */
class PaymentSession extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{payment_session}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, equity', 'required'),
			array('user_entry', 'numerical', 'integerOnly'=>true),
			array('equity', 'numerical'),
			array('date_entry', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, equity, date_entry, user_entry', 'safe', 'on'=>'search'),
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
			'user_rel'=>array(self::BELONGS_TO,'User','user_entry'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'equity' => Yii::t('order','Initial Capital'),
			'date_entry' => Yii::t('global','Date Entry'),
			'user_entry' => Yii::t('global','User Entry'),
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
		$criteria->compare('name',$this->name);
		$criteria->compare('equity',$this->equity);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('user_entry',$this->user_entry);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db2;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PaymentSession the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function hasSession()
	{
		$criteria=new CDBCriteria;
		$criteria->compare('name',md5(date("Y-m-d")));
		$count=self::model()->count($criteria);
		return ($count>0)? true : false;
	}

	public function getSession($name)
	{
		$criteria=new CDBCriteria;
		$criteria->compare('name',$name);
		$model=self::model()->find($criteria);
		return $model;
	}

	public function getModal($date)
	{
		$criteria=new CDBCriteria;
		$criteria->compare('name',md5($date));
		$model=self::model()->find($criteria);
		return $model->equity;
	}
}
