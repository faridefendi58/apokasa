<?php

/**
 * This is the model class for table "{{rbac_group}}".
 *
 * The followings are the available columns in table '{{rbac_group}}':
 * @property integer $id
 * @property string $group_name
 * @property integer $level
 */
class RbacGroup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RbacGroup the static model class
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
		return '{{rbac_group}}';
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
			array('group_name, level', 'required'),
			array('group_name','unique','on'=>'create'),
			array('level', 'numerical', 'integerOnly'=>true),
			array('group_name', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, group_name, level', 'safe', 'on'=>'search'),
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
			'user' => array(self::HAS_MANY, 'User', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'group_name' => 'Group Name',
			'level' => 'Level',
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
		$criteria->compare('group_name',$this->group_name,true);
		$criteria->compare('level',$this->level);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function listLevel($default=100)
	{
		for($i=1; $i<$default; $i++){
			$list[$i]=$i;
		}
		return array_reverse($list, true);
	}
	
	public function items($title='- Pilih Group -')
	{
		$criteria=new CDbCriteria;
		$criteria->order='level DESC';
		$models=self::model()->findAll($criteria);
		
		if(!empty($title))
			$data['']=$title;
		foreach($models as $model){
			$data[$model->id]=ucfirst($model->group_name);
		}
		return $data;
	}
}
