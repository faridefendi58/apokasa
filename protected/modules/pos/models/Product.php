<?php

/**
 * This is the model class for table "{{product}}".
 *
 * The followings are the available columns in table '{{product}}':
 * @property integer $id
 * @property string $barcode
 * @property string $name
 * @property string $description
 * @property string $tag
 * @property integer $status
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class Product extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{product}}';
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
            array('barcode, name, user_entry', 'required'),
            array('status, type, user_entry, user_update', 'numerical', 'integerOnly' => true),
            array('barcode', 'length', 'max' => 15),
            array('name', 'length', 'max' => 128),
            array('description, tag, date_entry, date_update', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, barcode, name, description, tag, status, date_entry, user_entry, date_update, user_update', 'safe', 'on' => 'search'),
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
            'price_rel' => array(self::HAS_MANY, 'ProductPrice', 'product_id'),
            'discount_rel' => array(self::HAS_MANY, 'ProductDiscount', 'product_id'),
            'discount_rel_count' => array(self::STAT, 'ProductDiscount', 'product_id'),
            'type_rel' => array(self::BELONGS_TO, 'ProductType', 'type'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'barcode' => 'Barcode',
            'name' => Yii::t('product', 'Name'),
            'description' => Yii::t('product', 'Description'),
            'tag' => 'Tag',
            'status' => 'Status',
            'type' => Yii::t('product', 'Type'),
            'date_entry' => Yii::t('global', 'Date Entry'),
            'user_entry' => Yii::t('global', 'User Entry'),
            'date_update' => Yii::t('global', 'Date Update'),
            'user_update' => Yii::t('global', 'User Update'),
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('barcode', $this->barcode, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('tag', $this->tag, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('type', $this->type);
        $criteria->compare('date_entry', $this->date_entry, true);
        $criteria->compare('user_entry', $this->user_entry);
        $criteria->compare('date_update', $this->date_update, true);
        $criteria->compare('user_update', $this->user_update);
        $criteria->order = 'id DESC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 30)
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Product the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function item($id)
    {
        return self::model()->findByPk($id);
    }

    public function items($title = null)
    {
        $criteria = new CDbCriteria;
        $criteria->order = 'name ASC';
        $models = self::model()->findAll($criteria);
        $items = array();
        if (!empty($title))
            $items[''] = $title;
        foreach ($models as $model) {
            $items[$model->id] = $model->name;
        }
        return $items;
    }

    public function getArrayData()
    {
        $models = self::model()->findAll();
        $items = array();
        foreach ($models as $model) {
            $items[$model->id] = array(
                'barcode' => $model->barcode,
                'name' => $model->name,
                'unit_price' => $model->price_rel->sold_price,
                'tag' => unserialize($model->tag)
            );
        }
        return $items;
    }

    public function getPrice($id = 0)
    {
        if ($id == 0)
            $id = $this->id;
        $criteria = new CDbCriteria;
        $criteria->compare('product_id', $id);
        $criteria->compare('current_stock', '>0');
        $criteria->order = 'id ASC';
        return ProductPrice::model()->find($criteria);
    }

    public function getDiscontedItems($product_id = 0)
    {
        if ($product_id <= 0)
            $product_id = $this->id;
        $criteria = new CDbCriteria;
        $criteria->compare('product_id', $product_id);
        $criteria->order = 'quantity DESC';
        $count = ProductDiscount::model()->count($criteria);
        $items = array();
        if ($count > 0) {
            $models = ProductDiscount::model()->findAll($criteria);
            foreach ($models as $model) {
                $items[] = $model;
            }
        }
        return $items;
    }

    /** $data: barcode, name */
    public function create_record($data)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('barcode', $data['detail']['barcode']);
        $criteria->compare('name', $data['detail']['name']);
        $count = self::model()->count($criteria);
        if ($count <= 0) {
            $model = new Product;
            $model->attributes = $data['detail'];
            if ($model->save()) {
                $product_id = $model->id;
                $model2 = new ProductPrice;
                $model2->product_id = $product_id;
                $model2->attributes = $data['prices'];
                $model2->save();
                return $model->id;
            }
        }
        return false;
    }

    public function list_items($title = null)
    {
        $sql = "SELECT CONCAT(barcode,' - ',name) AS item FROM tbl_product";
        $command = Yii::app()->db2->createCommand($sql);
        $items = array();
        if (!empty($title))
            $items[''] = $title;
        foreach ($command->query() as $row) {
            $name = $row['item'];
            $items[$name] = $name;
        }
        return $items;
    }
}
