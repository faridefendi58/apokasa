<?php

class ReportsController extends EController
{
	public static $_alias='Report';

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('view','detail','exportExcel','analytic','push'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionView()
	{
		$data_from = (!empty($_GET['Order']['date_from']))? strtotime($_GET['Order']['date_from']) : time()-(24*3600);
		$data_to = (!empty($_GET['Order']['date_to']))? strtotime($_GET['Order']['date_to']) : time();

		$rawData = array();
		$sum_of_total_pembelian = 0;
		$sum_of_total_pendapatan = 0;
		$sum_of_total_net_margin = 0;
		for ($i=$data_from; $i<=$data_to; $i=$i+86400) {
		    $total_pembelian = Order::getCountOrderItemDate(date('Y-m-d', $i));
		    $total_pendapatan = Order::getTotalOrderDate(date('Y-m-d', $i));
		    $total_net_margin = Order::getTotalMarginDate(date('Y-m-d', $i));

			$rawData[] = array(
					'date' => date('Y-m-d', $i),
					'total_pembelian' => $total_pembelian,
					'total_pendapatan' => $total_pendapatan,
					'total_net_margin' => $total_net_margin,
				);
            $sum_of_total_pembelian = $sum_of_total_pembelian + $total_pembelian;
            $sum_of_total_pendapatan = $sum_of_total_pendapatan + $total_pendapatan;
            $sum_of_total_net_margin = $sum_of_total_net_margin + $total_net_margin;
		}

		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'date-order',
			'sort' => array(
				'attributes' => array(
				     'id', 'username', 'email',
				),
			),
			'pagination' => array(
				'pageSize' => 20,
			),
		));

		$this->render('view',array(
			'dataProvider' => $dataProvider,
			'model' => new Order,
            'sum_of_total_pembelian' => $sum_of_total_pembelian,
            'sum_of_total_pendapatan' => $sum_of_total_pendapatan,
            'sum_of_total_net_margin' => $sum_of_total_net_margin
		));
	}

	public function actionDetail($date)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('DATE_FORMAT(date_entry, \'%Y-%m-%d\')',date("Y-m-d",strtotime($date)));
		$criteria->group='product_id';
		$criteria->order='date_entry ASC';

		$dataProvider=new CActiveDataProvider('Order',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>100)));
		$this->render('detail',array(
			'dataProvider'=>$dataProvider,
			'date'=>date("Y-m-d",strtotime($date)),
			'total_order'=>number_format(Order::getTotalOrderDate(date("Y-m-d",strtotime($date))),0,',','.'),
		));
	}	

	public function actionExportExcel($date)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('DATE_FORMAT(date_entry, \'%Y-%m-%d\')',date("Y-m-d",strtotime($date)));
		$criteria->group='product_id';
		$criteria->order='date_entry ASC';

		$model=Order::model()->findAll($criteria);
		$columns = array(
				array(
					'header'=>Yii::t('order','Item Name'),
					'value'=>'$data->title',
					'footer'=>'TOTAL',
				),
				array(
					'header'=>Yii::t('order','Total Item'),
					'value'=>'Order::getCountOrderItemDate(date("Y-m-d",strtotime($data->date_entry)),$data->product_id)',
					'footer'=>Order::getCountOrderItemDate(date("Y-m-d",strtotime($date))), 
				),
				array(
					'header'=>Yii::t('order','Price'),
					'value'=>'$data->price',
				),
				array(
					'header'=>Yii::t('order','Sub Total'),
					'value'=>'Order::getTotalOrderDate(date("Y-m-d",strtotime($data->date_entry)),$data->product_id)',
					'footer'=>Order::getTotalOrderDate(date("Y-m-d",strtotime($date))), 
				),
			);

		//$this->toExcel($model,$columns, 'Laporan Pendapatan '.date("d F Y",strtotime($date)), array(), 'Excel5');
		$this->toExcel($model,$columns, 'Laporan Pendapatan '.date("d F Y",strtotime($date)), array(), 'Excel2007');
	}

	public function actionAnalytic()
	{
		if(!Order::hasAnalyticConfig())
			return false;
		$criteria=new CDbCriteria;
		$criteria->compare('executed',0);
		$criteria->order='id DESC';

		$dataProvider=new CActiveDataProvider('Queue',array('criteria'=>$criteria));
		$this->render('analytic',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionPush()
	{
		if(Yii::app()->request->isAjaxRequest ){
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$criteria=new CDbCriteria;
			$criteria->compare('executed',0);
			$criteria->order='id ASC';

			$models=Queue::model()->findAll($criteria);
			foreach($models as $model){
				$curl=$this->get_curl(
					Yii::app()->config->get('analitik_url'),
					array(
						'data'=>$model->getToApiArray(0,true)
					)
				);
				$respon=CJSON::decode($curl);
				if($respon['invoice']){
					$model->executed=1;
					$model->status='success';
				}else{
					$model->executed=0;
					$model->status='failed';
				}
				$model->notes=$curl;
				$model->date_update=date(c);
				$model->user_update=Yii::app()->user->id;
				$model->update(array('executed','status','notes','date_update','user_update'));
			}

			echo CJSON::encode(array(
				'status'=>'success',					
				'div'=>'<div class="alert alert-success">Laporan berhasil diupload.</div>',
			));
			exit;
		}
	}

	private function encode_post($data)
	{
		$post_contents = '';
		foreach($data as $key => $val) {
			$post_contents .= ($post_contents ? '&' : '').urlencode($key).'='.urlencode($val);
		}
		return $post_contents;
	}

	private function get_curl($url, $post_vars = false)
	{
		//url-ify the data for the POST
		$fields_string='';
		foreach($post_vars as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($post_vars));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

		//execute post
		$result = curl_exec($ch);
		//close connection
		curl_close($ch);
		return $result;
	}
}
