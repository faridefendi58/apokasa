<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property string $id
 * @property integer $customer_id
 * @property string $product_id
 * @property string $promo_id
 * @property string $group_id
 * @property integer $group_master
 * @property string $title
 * @property integer $invoice_id
 * @property string $quantity
 * @property double $price
 * @property double $discount
 * @property string $status
 * @property string $notes
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class Order extends CActiveRecord
{
	public $date_from;
	public $date_to;
	public $range;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order}}';
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
			array('customer_id, group_master, invoice_id, status, type, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('price, discount', 'numerical'),
			array('product_id, promo_id, quantity', 'length', 'max'=>20),
			array('group_id, title', 'length', 'max'=>255),
			array('notes, date_update', 'safe'),
			array('date_from, date_to, cost_price, range', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, product_id, promo_id, group_id, group_master, title, invoice_id, quantity, price, discount, status, notes, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'customer_rel'=>array(self::BELONGS_TO,'Customer','customer_id'),
			'invoice_rel'=>array(self::BELONGS_TO,'Invoice','invoice_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => Yii::t('order', 'Customer'),
			'product_id' => Yii::t('order','Product'),
			'promo_id' => Yii::t('order','Promo'),
			'group_id' => Yii::t('order','Group'),
			'group_master' => Yii::t('order','Group Master'),
			'title' => Yii::t('order','Title'),
			'invoice_id' => Yii::t('order','Invoice'),
			'quantity' => Yii::t('order','Quantity'),
			'price' => Yii::t('order','Price'),
			'discount' => Yii::t('order','Discount'),
			'status' => Yii::t('order','Status'),
			'type' => Yii::t('order','Type'),
			'notes' => Yii::t('order','Notes'),
			'date_entry' => Yii::t('global','Date Entry'),
			'user_entry' => Yii::t('global','User Entry'),
			'date_update' => Yii::t('global','Date Update'),
			'user_update' => Yii::t('global','User Update'),
            'range' => Yii::t('invoice','Range'),
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('promo_id',$this->promo_id,true);
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('group_master',$this->group_master);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('invoice_id',$this->invoice_id);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('notes',$this->notes,true);
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
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getNextGroupId()
	{
		$criteria=new CDbCriteria;
		$criteria->order='group_id DESC';
		$model=self::model()->find($criteria);
		return $model->group_id+1;
	}

	public function getIncome($period='today')
	{
		switch($period){
			case 'today':
				$q=Yii::app()->db2->createCommand("SELECT SUM(t.price) AS tot FROM `tbl_invoice_item` t LEFT JOIN `tbl_invoice` i ON i.id=t.invoice_id WHERE DATE_FORMAT(t.date_entry, '%Y-%m-%d') = CURDATE()")->queryRow();
				$income=$q['tot'];
				break;
			case 'yesterday':
				$q=Yii::app()->db2->createCommand("SELECT SUM(t.price) AS tot FROM `tbl_invoice_item` t LEFT JOIN `tbl_invoice` i ON i.id=t.invoice_id WHERE DATEDIFF(CURDATE(), DATE_FORMAT(t.date_entry, '%Y-%m-%d')) = 1")->queryRow();
				$income=$q['tot'];
				break;
			case 'thismonth':
				$q=Yii::app()->db2->createCommand("SELECT SUM(t.price) AS tot FROM `tbl_invoice_item` t LEFT JOIN `tbl_invoice` i ON i.id=t.invoice_id WHERE month(t.date_entry) = EXTRACT(month FROM (NOW()))")->queryRow();
				$income=$q['tot'];
				break;
			case 'lastmonth':
				$q=Yii::app()->db2->createCommand("SELECT SUM(t.price) AS tot FROM `tbl_invoice_item` t LEFT JOIN `tbl_invoice` i ON i.id=t.invoice_id WHERE YEAR(t.date_entry) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(t.date_entry) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)")->queryRow();
				$income=$q['tot'];
				break;
			case 'total':
				$q=Yii::app()->db2->createCommand("SELECT SUM(t.price) AS tot FROM `tbl_invoice_item` t LEFT JOIN `tbl_invoice` i ON i.id=t.invoice_id WHERE i.status=1")->queryRow();
				$income=$q['tot'];
				break;
		}
		return $income;
	}

	public function getOrder($period='today')
	{
		switch($period){
			case 'today':
				$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_order` t WHERE t.status=1 AND DATE_FORMAT(t.date_entry, '%Y-%m-%d') = CURDATE()")->queryRow();
				$income=$q['tot'];
				break;
			case 'yesterday':
				$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_order` t WHERE t.status=1 AND DATEDIFF(CURDATE(), DATE_FORMAT(t.date_entry, '%Y-%m-%d')) = 1")->queryRow();
				$income=$q['tot'];
				break;
			case 'thismonth':
				$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_order` t WHERE t.status=1 AND month(t.date_entry) = EXTRACT(month FROM (NOW()))")->queryRow();
				$income=$q['tot'];
				break;
			case 'lastmonth':
				$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_order` t WHERE t.status=1 AND YEAR(t.date_entry) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(t.date_entry) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)")->queryRow();
				$income=$q['tot'];
				break;
			case 'total':
				$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_order` t WHERE t.status=1")->queryRow();
				$income=$q['tot'];
				break;
		}
		return $income;
	}

	public function getInvoice($period='today')
	{
		switch($period){
			case 'today':
				$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_invoice` t WHERE t.status=1 AND DATE_FORMAT(t.date_entry, '%Y-%m-%d') = CURDATE()")->queryRow();
				$income=$q['tot'];
				break;
			case 'yesterday':
				$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_invoice` t WHERE t.status=1 AND DATEDIFF(CURDATE(), DATE_FORMAT(t.date_entry, '%Y-%m-%d')) = 1")->queryRow();
				$income=$q['tot'];
				break;
			case 'thismonth':
				$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_invoice` t WHERE t.status=1 AND month(t.date_entry) = EXTRACT(month FROM (NOW()))")->queryRow();
				$income=$q['tot'];
				break;
			case 'lastmonth':
				$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_invoice` t WHERE t.status=1 AND YEAR(t.date_entry) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(t.date_entry) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)")->queryRow();
				$income=$q['tot'];
				break;
			case 'total':
				$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_invoice` t WHERE t.status=1")->queryRow();
				$income=$q['tot'];
				break;
		}
		return $income;
	}

	public function getRankOrder($limit=10)
	{
	    $sql = "SELECT SUM(t.quantity) AS tot, t.product_id, p.name 
          FROM `tbl_order` t 
          LEFT JOIN tbl_product p ON p.id=t.product_id 
          WHERE t.status = 1 
          GROUP BY t.product_id 
          ORDER BY tot DESC LIMIT ".$limit;

		$q = Yii::app()->db2->createCommand($sql)->queryAll();
		
		return $q;
	}

	public function getCountOrderDate($date,$product_id=0)
	{
		if($product_id==0){
			$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_order` t WHERE t.status=1 AND DATE_FORMAT(t.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d')")->queryRow();
			$tot=$q['tot'];
		}else{
			$q=Yii::app()->db2->createCommand("SELECT COUNT(*) AS tot FROM `tbl_order` t WHERE t.status=1 AND DATE_FORMAT(t.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') AND product_id = '".$product_id."'")->queryRow();
			$tot=$q['tot'];
		}
		
		return $tot;
	}

	public function getTotalOrderDate($date, $product_id=0, $shift=0)
	{
		if ($product_id == 0) {
			if ($shift == 0){
				$q=Yii::app()->db2->createCommand("SELECT SUM(t.price) AS tot FROM `tbl_invoice_item` t LEFT JOIN `tbl_invoice` i ON i.id=t.invoice_id WHERE i.status=1 AND DATE_FORMAT(i.paid_at, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d')")->queryRow();
				$tot=$q['tot'];
			} else {
				$daily_open=Yii::app()->config->get('daily_open');
				$shift_hour=Yii::app()->config->get('shift_hour');
				if($shift==1)
					$q=Yii::app()->db2->createCommand("SELECT SUM(t.price) AS tot FROM `tbl_invoice_item` t LEFT JOIN `tbl_order` i ON i.id=t.rel_id WHERE i.status=1 AND DATE_FORMAT(i.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') AND DATE_FORMAT(i.date_entry, '%H-%i') BETWEEN '".$daily_open."' AND '".$shift_hour."'")->queryRow();
				else
					$q=Yii::app()->db2->createCommand("SELECT SUM(t.price) AS tot FROM `tbl_invoice_item` t LEFT JOIN `tbl_order` i ON i.id=t.rel_id WHERE i.status=1 AND DATE_FORMAT(i.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') AND DATE_FORMAT(i.date_entry, '%H-%i') > '".$shift_hour."'")->queryRow();
				$tot=$q['tot'];
			}
		}else{
			if($shift>0){
				$daily_open=Yii::app()->config->get('daily_open');
				$shift_hour=Yii::app()->config->get('shift_hour');
				if($shift==1)
					$q=Yii::app()->db2->createCommand("SELECT SUM(t.price) AS tot FROM `tbl_invoice_item` t LEFT JOIN `tbl_order` i ON i.id=t.rel_id WHERE i.status=1 AND DATE_FORMAT(i.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') AND i.product_id = '".$product_id."' AND DATE_FORMAT(i.date_entry, '%H-%i') BETWEEN '".$daily_open."' AND '".$shift_hour."'")->queryRow();
				else
					$q=Yii::app()->db2->createCommand("SELECT SUM(t.price) AS tot FROM `tbl_invoice_item` t LEFT JOIN `tbl_order` i ON i.id=t.rel_id WHERE i.status=1 AND DATE_FORMAT(i.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') AND i.product_id = '".$product_id."' AND DATE_FORMAT(i.date_entry, '%H-%i') > '".$shift_hour."'")->queryRow();
				$tot=$q['tot'];
			}else{
				$q=Yii::app()->db2->createCommand("SELECT SUM(t.price) AS tot FROM `tbl_invoice_item` t LEFT JOIN `tbl_order` i ON i.id=t.rel_id WHERE i.status=1 AND DATE_FORMAT(t.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') AND i.product_id = '".$product_id."'")->queryRow();
				$tot=$q['tot'];
			}
		}
		
		return $tot;
	}

	/**
	 * get total item sold out product on specific date
	 */
	public function getCountOrderItemDate($date,$product_id=0,$shift=0)
	{
		if($product_id==0){
			if($shift==0){
				$q=Yii::app()->db2->createCommand("SELECT SUM(t.quantity) AS tot FROM `tbl_order` t WHERE DATE_FORMAT(t.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d')")->queryRow();
				$tot=$q['tot'];
			}else{
				$date=date("Y-m-d",strtotime($date));
				$daily_open=Yii::app()->config->get('daily_open');
				$shift_hour=Yii::app()->config->get('shift_hour')+1;
				if((int)$shift==1)
					$q=Yii::app()->db2->createCommand("SELECT SUM(t.quantity) AS tot FROM `tbl_order` t WHERE DATE_FORMAT(t.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') AND DATE_FORMAT(t.date_entry, '%H-%i') BETWEEN '".$daily_open."' AND '".$shift_hour."'")->queryRow();
				else
					$q=Yii::app()->db2->createCommand("SELECT SUM(t.quantity) AS tot FROM `tbl_order` t WHERE DATE_FORMAT(t.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') AND DATE_FORMAT(t.date_entry, '%H-%i') > '".$shift_hour."'")->queryRow();
				$tot=$q['tot'];
			}
		}else{
			if((int)$shift>0){
				$date=date("Y-m-d",strtotime($date));
				$daily_open=Yii::app()->config->get('daily_open');
				$shift_hour=Yii::app()->config->get('shift_hour')+1;
				if((int)$shift==1)
					$q=Yii::app()->db2->createCommand("SELECT SUM(t.quantity) AS tot FROM `tbl_order` t WHERE DATE_FORMAT(t.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') AND product_id = '".$product_id."' AND DATE_FORMAT(t.date_entry, '%H-%i') BETWEEN '".$daily_open."' AND '".$shift_hour."'")->queryRow();
				else
					$q=Yii::app()->db2->createCommand("SELECT SUM(t.quantity) AS tot FROM `tbl_order` t WHERE DATE_FORMAT(t.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') AND product_id = '".$product_id."' AND DATE_FORMAT(t.date_entry, '%H-%i') > '".$shift_hour."'")->queryRow();
				$tot=$q['tot'];
			}else{
				$q=Yii::app()->db2->createCommand("SELECT SUM(t.quantity) AS tot FROM `tbl_order` t WHERE DATE_FORMAT(t.date_entry, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') AND product_id = '".$product_id."'")->queryRow();
				$tot=$q['tot'];
			}
		}
		
		return (int)$tot;
	}

	public function get_period_interval($data=array())
	{
		if(!empty($data['date_from']))
			$date_from=$data['date_from'];
		else{
			//if(date("d")<=15)
				$date_from=date("Y-m-01");
			//else
				//$date_from=date("Y-m-16");
		}
		
		if(!empty($data['date_to']))
			$date_to=$data['date_to'];
		else
			$date_to=date("Y-m-t");
		
		$startDate = new DateTime($date_from,new DateTimeZone("Asia/Jakarta"));
		$endDate = new DateTime($date_to,new DateTimeZone("Asia/Jakarta"));
		$periodInterval = new DateInterval("P1D"); // 1-day, though can be more sophisticated rule
		$period = new DatePeriod($startDate, $periodInterval, $endDate);
		return $period;
	}

	public function getStatistikMonthly($type='income')
	{
		$items=array();
		$items[0]=array(0,0);
		foreach(self::get_period_interval() as $date){
			if($type=='income')
				$items[]=array($date->format("d"),self::getTotalOrderDate($date->format("Y-m-d")));
			elseif($type=='tot_order')
				$items[]=array($date->format("d"),self::getCountOrderItemDate($date->format("Y-m-d")));
		}
		return $items;
	}
	
	public function getGroupByInvoice($invoice_id)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('invoice_id',$invoice_id);
		$model=self::model()->find($criteria);
		return $model->group_id;
	}

	public function hasAnalyticConfig()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.key','analitik_url');
		$count=PParams::model()->count($criteria);
		if($count<=0){
			$qry = 'SHOW TABLES';
			$command = Yii::app()->db2->createCommand($qry);
			$qry=$command->query();
			$items=array();
			foreach($qry as $row){
				$tbl=array_values($row);
				$items[]=$tbl[0];
			}
			if(!in_array('tbl_queue',$items)){
				$qry2 = "CREATE TABLE IF NOT EXISTS `tbl_queue` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `invoice_id` int(11) NOT NULL,
				  `executed` int(11) NOT NULL DEFAULT '0',
				  `status` varchar(16) DEFAULT NULL,
				  `notes` text,
				  `date_entry` datetime NOT NULL,
				  `user_entry` int(11) NOT NULL,
				  `date_update` datetime DEFAULT NULL,
				  `user_update` int(11) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
				$command2 = Yii::app()->db2->createCommand($qry2);
				$command2->execute();
			}
			$model=new PParams;
			$model->params_name='analitik url';
			$model->key='analitik_url';
			$model->value=serialize('http://bangido.solusidatastatistik.com/appadmin/api/invoices');
			$model->type=1;
			$model->notes='Push online analitik data';
			if(!$model->save())
				return false;
		}
		return true;
	}

    public function getMargin($period = 'today')
    {
        switch ($period) {
            case 'today':
                $sql = "SELECT SUM((t.price - t.cost_price)*t.quantity) AS tot 
                  FROM `tbl_order` t 
                  LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
                  WHERE i.status = 1 
                  AND DATE_FORMAT(t.date_entry, '%Y-%m-%d') = CURDATE()";

                $q = Yii::app()->db2->createCommand($sql)->queryRow();
                $income = $q['tot'];
                break;
            case 'yesterday':
                $sql = "SELECT SUM((t.price - t.cost_price)*t.quantity) AS tot 
                  FROM `tbl_order` t 
                  LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
                  WHERE i.status = 1 
                  AND DATEDIFF(CURDATE(), DATE_FORMAT(t.date_entry, '%Y-%m-%d')) = 1";

                $q = Yii::app()->db2->createCommand($sql)->queryRow();
                $income = $q['tot'];
                break;
            case 'thismonth':
                $sql = "SELECT SUM((t.price - t.cost_price)*t.quantity) AS tot 
                  FROM `tbl_order` t 
                  LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
                  WHERE i.status = 1 
                  AND month(t.date_entry) = EXTRACT(month FROM (NOW()))";

                $q = Yii::app()->db2->createCommand($sql)->queryRow();
                $income = $q['tot'];
                break;
            case 'lastmonth':
                $sql = "SELECT SUM((t.price - t.cost_price)*t.quantity) AS tot 
                  FROM `tbl_order` t 
                  LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
                  WHERE i.status = 1 
                  AND YEAR(t.date_entry) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(t.date_entry) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";

                $q = Yii::app()->db2->createCommand($sql)->queryRow();
                $income = $q['tot'];
                break;
            case 'total':
                $sql = "SELECT SUM((t.price - t.cost_price)*t.quantity) AS tot 
                  FROM `tbl_order` t 
                  LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
                  WHERE i.status = 1";

                $q = Yii::app()->db2->createCommand($sql)->queryRow();
                $income = $q['tot'];
                break;
        }

        return $income;
    }

    public function getSoldItem($period = 'today')
    {
        switch ($period) {
            case 'today':
                $sql = "SELECT SUM(t.quantity) AS tot 
                  FROM `tbl_order` t 
                  LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
                  WHERE i.status = 1 
                  AND DATE_FORMAT(t.date_entry, '%Y-%m-%d') = CURDATE()";

                $q = Yii::app()->db2->createCommand($sql)->queryRow();
                $income = $q['tot'];
                break;
            case 'yesterday':
                $sql = "SELECT SUM(t.quantity) AS tot 
                  FROM `tbl_order` t 
                  LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
                  WHERE i.status = 1 
                  AND DATEDIFF(CURDATE(), DATE_FORMAT(t.date_entry, '%Y-%m-%d')) = 1";

                $q = Yii::app()->db2->createCommand($sql)->queryRow();
                $income = $q['tot'];
                break;
            case 'thismonth':
                $sql = "SELECT SUM(t.quantity) AS tot 
                  FROM `tbl_order` t 
                  LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
                  WHERE i.status = 1 
                  AND month(t.date_entry) = EXTRACT(month FROM (NOW()))";

                $q = Yii::app()->db2->createCommand($sql)->queryRow();
                $income = $q['tot'];
                break;
            case 'lastmonth':
                $sql = "SELECT SUM(t.quantity) AS tot 
                  FROM `tbl_order` t 
                  LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
                  WHERE i.status = 1 
                  AND YEAR(t.date_entry) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(t.date_entry) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";

                $q = Yii::app()->db2->createCommand($sql)->queryRow();
                $income = $q['tot'];
                break;
            case 'total':
                $sql = "SELECT SUM(t.quantity) AS tot 
                  FROM `tbl_order` t 
                  LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
                  WHERE i.status = 1";

                $q = Yii::app()->db2->createCommand($sql)->queryRow();
                $income = $q['tot'];
                break;
        }

        return $income;
    }

    public function getTotalMarginDate($date, $product_id = 0)
    {
        if ($product_id == 0) {
            $sql = "SELECT SUM((t.price - t.cost_price) * t.quantity) AS tot 
              FROM `tbl_order` t 
              LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
              WHERE i.status = 1 AND DATE_FORMAT(i.paid_at, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d')";

            $q = Yii::app()->db2->createCommand($sql)->queryRow();

            $tot = $q['tot'];
        } else {
            $sql = "SELECT SUM((t.price - t.cost_price) * t.quantity) AS tot 
              FROM `tbl_order` t 
              LEFT JOIN `tbl_invoice` i ON i.id = t.invoice_id 
              WHERE i.status = 1 AND DATE_FORMAT(i.paid_at, '%Y-%m-%d') = DATE_FORMAT('".$date."', '%Y-%m-%d') 
                AND t.product_id = ".$product_id;

            $q = Yii::app()->db2->createCommand($sql)->queryRow();

            $tot = $q['tot'];
        }

        return $tot;
    }
}
