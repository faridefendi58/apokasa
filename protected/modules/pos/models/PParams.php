<?php

/**
 * This is the model class for table "{{params}}".
 *
 * The followings are the available columns in table '{{params}}':
 * @property integer $id
 * @property string $params_name
 * @property string $value
 * @property string $notes
 */
class PParams extends CActiveRecord
{
	public $image;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Params the static model class
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
		return '{{params}}';
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
			array('params_name, key, value', 'required'),
			array('params_name', 'length', 'max'=>128),
			array('type, notes', 'safe'),
			array('image','file','allowEmpty'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, params_name, value, notes', 'safe', 'on'=>'search'),
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
			'params_name' => Yii::t('params','Params Name'),
			'value' => Yii::t('params','Value'),
			'notes' => Yii::t('params','Notes'),
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
		$criteria->compare('params_name',$this->params_name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('notes',$this->notes,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>20),
		));
	}
	
	public function item($params_name)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('LOWER(params_name)',$params_name);
		
		$model=self::model()->find($criteria);
		return $model->value;
	}
}
