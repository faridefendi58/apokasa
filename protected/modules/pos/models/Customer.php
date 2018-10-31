<?php

/**
 * This is the model class for table "{{customer}}".
 *
 * The followings are the available columns in table '{{customer}}':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $telephone
 * @property string $address
 * @property integer $status
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class Customer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
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
		return '{{customer}}';
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
			array('name, status, user_entry', 'required'),
			array('status, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('email', 'length', 'max'=>96),
			array('email','email','allowEmpty'=>true),
			array('telephone', 'length', 'max'=>32),
			array('address, country_id, company, date_entry, date_update', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, email, telephone, address, status, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'country_rel'=>array(self::BELONGS_TO,'Country','country_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => Yii::t('customer','Name'),
			'email' => Yii::t('customer','Email'),
			'telephone' => Yii::t('customer','Telephone'),
			'address' => Yii::t('customer','Address'),
			'status' => Yii::t('customer','Status'),
			'date_entry' => Yii::t('global','Date Entry'),
			'user_entry' => Yii::t('global','User Entry'),
			'date_update' => Yii::t('global','Date Update'),
			'user_update' => Yii::t('global','User Update'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('user_entry',$this->user_entry);
		$criteria->compare('date_update',$this->date_update,true);
		$criteria->compare('user_update',$this->user_update);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function items($title=null)
	{
		$criteria=new CDbCriteria;
		$criteria->order='name ASC';
		$models=self::model()->findAll($criteria);
		$items=array();
		if(!empty($title))
			$items['']=$title;
		foreach($models as $model){
			$items[$model->id]=$model->name;
		}
		return $items;
	}

	public function list_items($title=null)
	{
		$sql="SELECT CONCAT(id,' - ',name) AS item FROM tbl_customer";
		$command=Yii::app()->db2->createCommand($sql);
		$items=array();
		if(!empty($title))
			$items['']=$title;
		foreach($command->query() as $row){
			$name=$row['item'];
			$items[$name]=$name;
		}
		return $items;
	}

	public function get_last_item()
    {
        $sql = "SELECT CONCAT(id,' - ',name) AS item FROM tbl_customer ORDER BY id DESC LIMIT 1";
        $command = Yii::app()->db2->createCommand($sql);

        $row = $command->queryRow();

        return $row['item'];
    }
}
