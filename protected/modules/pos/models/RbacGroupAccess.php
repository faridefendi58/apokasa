<?php

/**
 * This is the model class for table "{{rbac_group_access}}".
 *
 * The followings are the available columns in table '{{rbac_group_access}}':
 * @property integer $id
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property integer $group_id
 * @property integer $status
 */
class RbacGroupAccess extends CActiveRecord
{
	public $access;
	public $check_list;
	public $check_all;
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RbacGroupAccess the static model class
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
		return '{{rbac_group_access}}';
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
			array('module, controller, group_id', 'required'),
			array('group_id', 'numerical', 'integerOnly'=>true),
			array('module, controller', 'length', 'max'=>30),
			array('access, check_list, check_all','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, module, controller, group_id', 'safe', 'on'=>'search'),
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
			'module' => 'Module',
			'controller' => 'Controller',
			'group_id' => 'Group',
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
		$criteria->compare('module',$this->module,true);
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('group_id',$this->group_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/* public function listAccess($user_id)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('group_id',User::getGroup($user_id));
		$criteria->compare('status',1);
		
		$models=self::model()->findAll($criteria);
		$data=array();
		foreach($models as $model){
			$data[]=array('module'=>$model->module,'controller'=>$model->controller,'action'=>$model->action);
		}
		return $data;
	} */
	
	public function listAccess($user_id)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('group_id',User::getGroup($user_id));
		
		$models=self::model()->findAll($criteria);
		$data=array();
		foreach($models as $model){
			$data[$model->module][$model->controller]=array(
						'create_p'=>($model->create_p>0)? true : false,
						'read_p'=>($model->read_p>0)? true : false,
						'update_p'=>($model->update_p>0)? true : false,
						'delete_p'=>($model->delete_p>0)? true : false,
					);
		}
		
		return $data;
	}
	
	public function isChecked($module,$controller,$group_id,$priv)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('module',strtolower($module));
		$criteria->compare('controller',strtolower($controller));
		$criteria->compare('group_id',$group_id);
		$model=self::model()->find($criteria);
		if(count($model)>0){
			return ($model->$priv>0)? true : false;
		}else{
			return false;
		}
	}
}
