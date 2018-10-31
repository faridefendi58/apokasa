<?php

/**
 * This is the model class for table "{{invoice}}".
 *
 * The followings are the available columns in table '{{invoice}}':
 * @property string $id
 * @property integer $customer_id
 * @property string $serie
 * @property integer $nr
 * @property double $refund
 * @property string $notes
 * @property string $status
 * @property string $paid_at
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class Invoice extends CActiveRecord
{
    public $date_from;
    public $date_to;
    public $range;

    const STATUS_PAID = 1;
    const STATUS_UNPAID = 0;
    const STATUS_REFUND = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{invoice}}';
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
            array('nr, date_entry, user_entry', 'required'),
            array('customer_id, nr, user_entry, user_update', 'numerical', 'integerOnly' => true),
            array('refund', 'numerical'),
            array('serie, status', 'length', 'max' => 50),
            array('cash, notes, paid_at, date_update', 'safe'),
            array('config, date_from, date_to, currency_id, change_value, range', 'safe'),
            array('nr', 'check_serie_nr', 'on' => 'create'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, customer_id, serie, nr, refund, notes, status, paid_at, date_entry, user_entry, date_update, user_update', 'safe', 'on' => 'search'),
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
            'items_rel' => array(self::HAS_MANY, 'InvoiceItem', 'invoice_id'),
            'items_count' => array(self::STAT, 'InvoiceItem', 'invoice_id'),
            'customer_rel' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'user_entry_rel' => array(self::BELONGS_TO, 'User', 'user_entry'),
            'currency_rel' => array(self::BELONGS_TO, 'Currency', 'currency_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'customer_id' => Yii::t('invoice', 'Customer'),
            'serie' => 'Serie',
            'nr' => 'Nr',
            'refund' => 'Refund',
            'notes' => Yii::t('invoice', 'Notes'),
            'status' => 'Status',
            'paid_at' => Yii::t('invoice', 'Paid At'),
            'date_entry' => Yii::t('global', 'Date Entry'),
            'user_entry' => Yii::t('global', 'User Entry'),
            'date_update' => Yii::t('global', 'Date Update'),
            'user_update' => Yii::t('global', 'User Update'),
            'date_from' => Yii::t('invoice', 'Period From'),
            'date_to' => Yii::t('invoice', 'Period To'),
            'range' => Yii::t('invoice', 'Range'),
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('serie', $this->serie, true);
        $criteria->compare('nr', $this->nr);
        $criteria->compare('refund', $this->refund);
        $criteria->compare('notes', $this->notes, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('paid_at', $this->paid_at, true);
        $criteria->compare('date_entry', $this->date_entry, true);
        $criteria->compare('user_entry', $this->user_entry);
        $criteria->compare('date_update', $this->date_update, true);
        $criteria->compare('user_update', $this->user_update);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Invoice the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getInvoiceNumber($status, $type)
    {
        $status_name = strtolower(PLookup::item('InvoiceStatus', $status));
        $serie = Yii::app()->config->get($status_name . '_invoice_series');
        $criteria = new CDbCriteria;
        $criteria->compare('status', $status);
        $criteria->order = 'nr DESC';
        $model = self::model()->find($criteria);
        $next_nr = $model->nr + 1;
        if ($type == 'serie')
            return $serie;
        else
            return $next_nr;
    }

    public function getInvoiceFormatedNumber($id = 0)
    {
        if ($id == 0)
            $model = $this;
        else
            $model = self::model()->findByPk($id);

        $nr = str_repeat('0', 4 - strlen($model->nr));
        return $model->serie . $nr . $model->nr;
    }

    public function getTotalPrice($id = 0)
    {
        if ($id == 0)
            $model = $this;
        else
            $model = self::model()->findByPk($id);

        $tot = 0;
        if ($model->items_rel > 0) {
            foreach ($model->items_rel as $item) {
                $tot = $tot + $item->price;
            }
        }
        return $tot;
    }

    public function getTotalDiscount($id = 0)
    {
        if ($id == 0)
            $id = $this->id;
        $query = 'SELECT SUM(discount) AS amount FROM tbl_order WHERE invoice_id=' . $id . '';
        $rows = Yii::app()->db2->createCommand($query)->queryAll();
        return $rows[0]['amount'];
    }

    public function getChange($id = 0)
    {
        if ($id == 0)
            $model = $this;
        else
            $model = self::model()->findByPk($id);
        return $model->cash - $model->totalPrice;
    }

    public function getTotalPaid()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('status', 1);
        return self::model()->count($criteria);
    }

    public function has_serie_nr($serie, $nr)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('serie', $serie);
        $criteria->compare('nr', $nr);
        $count = self::model()->count($criteria);
        return ($count > 0) ? true : false;
    }

    public function check_serie_nr($attribute, $params)
    {
        if (self::has_serie_nr($this->serie, $this->nr))
            $this->addError('nr', 'Invoice Number is already taken.');
    }

    public function getItemsInArray($id = 0)
    {
        if ($id == 0)
            $model = $this;
        else
            $model = self::model()->findByPk($id);
        $items = array();
        foreach ($model->items_rel as $item) {
            $items[] = array(
                'item' => $item->attributes,
                'order' => $item->order_rel->attributes,
                'product' => array(
                    'detail' => $item->order_rel->product_rel->attributes,
                    'prices' => $item->order_rel->product_rel->price->attributes,
                ),
            );
        }
        return $items;
    }

    public function getTotalCost($id = 0)
    {
        if ($id == 0)
            $id = $this->id;

        $query = 'SELECT SUM(cost_price*quantity) AS amount FROM tbl_order WHERE invoice_id=' . $id . '';
        $rows = Yii::app()->db2->createCommand($query)->queryAll();

        return $rows[0]['amount'];
    }

    public function getNetProfit($id = 0)
    {
        if ($id == 0)
            $id = $this->id;

        $query = 'SELECT SUM((price - cost_price)*quantity) AS amount FROM tbl_order WHERE invoice_id=' . $id . '';
        $rows = Yii::app()->db2->createCommand($query)->queryAll();

        return $rows[0]['amount'];
    }
}
