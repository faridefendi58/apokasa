<?php

class OrdersController extends EController
{
    public $layout = 'column2';

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
                'actions' => array(
                    'create', 'suggestItems', 'scan', 'updateQty',
                    'paymentRequest', 'changeRequest', 'deleteItem', 'cancelTransaction', 'addmodal'
                ),
                'expression' => 'Rbac::ruleAccess(\'create_p\')',
            ),
            array('allow',
                'actions' => array('viewItems', 'print', 'promocode'),
                'users' => array('@'),
            ),
            array('allow',
                'actions' => array('view', 'listCustomer'),
                'expression' => 'Rbac::ruleAccess(\'read_p\')',
            ),
            array('allow',
                'actions' => array(
                    'update', 'change', 'createDiscount', 'setCurrency',
                    'paymentRequestUpdate', 'setTransactionType'
                ),
                'expression' => 'Rbac::ruleAccess(\'update_p\')',
            ),
            array('allow',
                'actions' => array('delete'),
                'expression' => 'Rbac::ruleAccess(\'delete_p\')',
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     */
    public function actionCreate()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('status', 1);
        $items_count = Product::model()->count($criteria);
        if ($items_count <= 1000) {
            Yii::app()->user->setState('items_data', Product::getArrayData());
        }

        if (!Yii::app()->user->hasState('items_belanja'))
            Yii::app()->user->setState('promocode', null);
        else {
            if (count(Yii::app()->user->getState('items_belanja')) == 0)
                Yii::app()->user->setState('promocode', null);
        }
        if (Yii::app()->user->hasState('promocode'))
            $promocode = Promo::model()->findByPk(Yii::app()->user->getState('promocode'))->code;
        if (!Yii::app()->user->hasState('currency'))
            Yii::app()->user->setState('currency', Currency::getDefault());
        //check whether there is equity
        if ((int)Yii::app()->config->get('use_initial_capital') > 0) {
            if (!PaymentSession::hasSession())
                $this->forward('addmodal');
        }

        if (!Yii::app()->user->hasState('transaction_type')) {
            Yii::app()->user->setState('transaction_type', Invoice::STATUS_PAID);
        }

        $model = null;

        $this->render('create', array(
            'model' => $model,
            'promocode' => $promocode,
        ));
    }

    public function actionAddmodal()
    {
        $model = new PaymentSession;
        if (isset($_POST['PaymentSession'])) {
            $model->attributes = $_POST['PaymentSession'];
            $model->equity = $this->money_unformat($model->equity);
            $model->name = md5(date("Y-m-d"));
            $model->date_entry = date(c);
            $model->user_entry = Yii::app()->user->id;
            if ($model->save()) {
                $this->redirect(array('create'));
            }
        }
        $this->render('add_modal', array('model' => $model));
    }

    /**
     * For autocomplete only
     */
    public function actionSuggestItems()
    {
        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {
            $items = array();
            $keyword = strtolower($keyword);
            if (Yii::app()->user->hasState('items_data')){
                foreach (Yii::app()->user->getState('items_data') as $item_id=>$data){
                    if ((is_array($data['tag']) && in_array($keyword,$data['tag']))
                        || strripos(strtolower($data['name']),$keyword)
                        || strripos($data['barcode'],$keyword)
                    ){
                        $items[]=$data['barcode'].' - '.$data['name'];
                    }
                }
            } else {
                $sql = "SELECT CONCAT(barcode,' - ',name) AS item 
                        FROM tbl_product 
                        WHERE LOWER(barcode) LIKE '%" . $keyword . "%' OR LOWER(name) LIKE '%" . $keyword . "%' OR tag REGEXP '" . $keyword . "' 
                        LIMIT 100";

                $command = Yii::app()->db2->createCommand($sql);
                $items = array();
                foreach ($command->query() as $row) {
                    $items[] = $row['item'];
                }
            }

            if (is_array($items))
                echo implode("\n", $items);
        }
    }

    public function actionScan()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            if (!empty($_POST['item'])) {
                $pecah = explode(" - ", $_POST['item']);
                $criteria = new CDbCriteria;
                $criteria->compare('barcode', $pecah[0]);
                $count = Product::model()->count($criteria);
                if ($count > 0) {
                    $model = Product::model()->find($criteria);
                    if ($model->price->current_stock >= 1) { //jika persediaan masih ada
                        if (!Yii::app()->user->hasState('items_belanja'))
                            Yii::app()->user->setState('items_belanja', array());

                        $currency = null;

                        $items = array(
                            'id' => $model->id,
                            'barcode' => $model->barcode,
                            'name' => $model->name,
                            'desc' => $model->description,
                            'cost_price' => $model->price->purchase_price,
                            'unit_price' => $model->price->sold_price,
                            'qty' => 1,
                            'discount' => 0,
                            'currency' => $currency,
                            'change_value' => Currency::getChangeValue($currency),
                        );

                        $items_belanja = Yii::app()->user->getState('items_belanja');
                        $new_items_belanja = array();
                        if (count($items_belanja) > 0) {
                            $any = 0;
                            foreach ($items_belanja as $index => $data) {
                                if ($data['id'] == $items['id']) {
                                    $data['qty'] = $data['qty'] + 1;
                                    $any = $any + 1;
                                }
                                $new_items_belanja[] = $data;
                            }
                            if ($any <= 0)
                                array_push($new_items_belanja, $items);
                        } else {
                            array_push($new_items_belanja, $items);
                        }
                        //renew state
                        Yii::app()->user->setState('items_belanja', $new_items_belanja);

                        echo CJSON::encode(array(
                            'status' => 'success',
                            'div' => $this->renderPartial('_items', array('model' => $model), true, true),
                            'subtotal' => number_format($this->getTotalBelanja(), 0, ',', '.'),
                        ));
                    } else {
                        echo CJSON::encode(array(
                            'status' => 'failed',
                            'message' => $model->name . ' is out of stock.',
                        ));
                    }
                } else {
                    echo CJSON::encode(array(
                        'status' => 'failed',
                        'message' => 'No item found!'
                    ));
                }
                exit;
            }
        }
    }

    public function actionUpdateQty()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            if (Yii::app()->user->hasState('items_belanja')) {
                $items_belanja = Yii::app()->user->getState('items_belanja');
                $id = $_POST['id'];
                $cart_discount = $items_belanja[$id]['discount'] / $items_belanja[$id]['qty'];
                $items_belanja[$id]['qty'] = (int)$_POST['qty'];
                $model = Product::model()->findByPk($items_belanja[$id]['id']);
                if ((int)$_POST['qty'] <= (int)$model->price->current_stock) { //jika kurang dari atau sm dengan persediaan
                    $price = 0;
                    if ($model->discount_rel_count > 0) {
                        $discount_founded = false;
                        foreach ($model->getDiscontedItems() as $index => $data) {
                            if ($data->quantity <= 0)
                                $data->quantity = 1;

                            if ($items_belanja[$id]['qty'] >= $data->quantity && $items_belanja[$id]['qty'] <= $data->quantity_max) {
                                if (time() >= strtotime($data->date_start) && time() <= strtotime($data->date_end)) {
                                    $price = $data->base_price;
                                    if (Yii::app()->user->hasState('transaction_type') && Yii::app()->user->getState('transaction_type') == Invoice::STATUS_REFUND) {
                                        $price = -1 * $price;
                                    }
                                    $items_belanja[$id]['unit_price'] = $price;
                                    $items_belanja[$id]['discount'] = ($items_belanja[$id]['unit_price'] - $price) * $items_belanja[$id]['qty'];
                                    $discount_founded = true;
                                } else {
                                    $items_belanja[$id]['discount'] = 0;
                                }
                            } else {
                                if (!$discount_founded) {
                                    $items_belanja[$id]['discount'] = 0;
                                    $price = $model->price->sold_price;
                                    if (Yii::app()->user->hasState('transaction_type') && Yii::app()->user->getState('transaction_type') == Invoice::STATUS_REFUND) {
                                        $price = -1 * $price;
                                    }
                                    $items_belanja[$id]['unit_price'] = $price;
                                }
                            }
                        }
                    } else {
                        $price = $model->price->sold_price * $_POST['qty'];
                        if (Yii::app()->user->hasState('transaction_type') && Yii::app()->user->getState('transaction_type') == Invoice::STATUS_REFUND) {
                            $price = -1 * $price;
                        }
                        if (Yii::app()->user->hasState('promocode'))
                            $items_belanja[$id]['discount'] = Promo::getDiscountValue(Yii::app()->user->getState('promocode'), $price);
                        $items_belanja[$id]['discount'] = 0;
                    }

                    // update the session
                    Yii::app()->user->setState('items_belanja', $items_belanja);
                    if ($price > 0)
                        $total = $price * $items_belanja[$id]['qty'];
                    else
                        $total = $items_belanja[$id]['unit_price'] * $items_belanja[$id]['qty'];
                    //$discount=($model->price->sold_price*$_POST['qty'])-$total;
                    $discount = $items_belanja[$id]['discount'];

                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => (int)$_POST['qty'],
                        'total' => number_format($total, 0, ',', '.'),
                        'subtotal' => number_format($this->getTotalBelanja(), 0, ',', '.'),
                        'discount' => number_format($discount, 0, ',', '.'),
                        'unit_price' => number_format($items_belanja[$id]['unit_price'], 0, ',', '.')
                    ));
                } else {
                    echo CJSON::encode(array(
                        'status' => 'failed',
                        'message' => $_POST['qty'] . ' is not allowed, max ' . $model->price->current_stock . ' ready stock.',
                    ));
                }
                exit;
            }
        }
    }

    public function actionPaymentRequest()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            //Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            //Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

            $model = new PaymentForm;
            if (isset($_POST['PaymentForm'])) {
                if (Yii::app()->user->hasState('items_belanja')) {
                    $model2 = new Invoice;
                    if (Yii::app()->user->hasState('customer')) {
                        $customer = Yii::app()->user->getState('customer');
                        $model2->customer_id = (!empty($customer)) ? $customer->id : 0;
                    }
                    $model2->status = 1;
                    $model2->cash = $this->money_unformat($_POST['PaymentForm']['amount_tendered']);
                    $model2->serie = $model2->getInvoiceNumber($model2->status, 'serie');
                    $model2->nr = $model2->getInvoiceNumber($model2->status, 'nr');
                    if ($model2->status == 1)
                        $model2->paid_at = date(c);
                    $model2->config = CJSON::encode(
                        array(
                            'items_belanja' => Yii::app()->user->getState('items_belanja'),
                            'items_payment' => Yii::app()->user->getState('items_payment'),
                            'customer' => Yii::app()->user->getState('customer'),
                            'promocode' => Yii::app()->user->getState('promocode'),
                        )
                    );
                    $model2->currency_id = Yii::app()->user->getState('currency');
                    $model2->change_value = Currency::getChangeValue($model2->currency_id);
                    if (!empty($_POST['PaymentForm']['notes'])) {
                        $model2->notes = $_POST['PaymentForm']['notes'];
                    }
                    $model2->date_entry = date(c);
                    $model2->user_entry = Yii::app()->user->id;
                    if ($model2->save()) {
                        $invoice_id = $model2->id;
                        $group_id = Order::getNextGroupId();
                        foreach (Yii::app()->user->getState('items_belanja') as $index => $data) {
                            $model3 = new Order;
                            $model3->product_id = $data['id'];
                            $model3->customer_id = $model2->customer_id;
                            $product = Product::item($model3->product_id);
                            $model3->title = $product->name;
                            $model3->group_id = $group_id;
                            $model3->group_master = ($index == 0) ? 1 : 0;
                            $model3->invoice_id = $model2->id;
                            $model3->quantity = $data['qty'];
                            //$model3->price = $product->price->sold_price;
                            $model3->price = $data['unit_price'];
                            $model3->discount = $data['discount'];
                            $model3->cost_price = $data['cost_price'];
                            if (Yii::app()->user->hasState('promocode')) {
                                $model3->promo_id = Yii::app()->user->getState('promocode');
                                $model3->discount = Promo::getDiscountValue(Yii::app()->user->getState('promocode'), $model3->price);
                            }
                            $model3->currency_id = $model2->currency_id;
                            $model3->change_value = $model2->change_value;
                            $model3->type = $_POST['PaymentForm']['type'];
                            $model3->status = 1;
                            $model3->date_entry = date(c);
                            $model3->user_entry = Yii::app()->user->id;
                            if ($model3->save()) {
                                if ((int)Yii::app()->config->get('substract_stock') > 0) {
                                    $product->price->current_stock = $product->price->current_stock - $model3->quantity;
                                    if (!$product->price->update(array('current_stock'))) {
                                        var_dump($product->price->errors);
                                        exit;
                                    }
                                }

                                $model4 = new InvoiceItem;
                                $model4->invoice_id = $model2->id;
                                $model4->type = 'order';
                                $model4->rel_id = $model3->id;
                                $model4->title = $model3->title;
                                $model4->quantity = $model3->quantity;
                                $model4->price = $model3->quantity * ($model3->price - $model3->discount);
                                $model4->date_entry = date(c);
                                $model4->user_entry = Yii::app()->user->id;
                                $model4->save();
                            }
                        }
                        Yii::app()->user->setState('items_belanja', null);
                        Yii::app()->user->setState('items_payment', null);
                        Yii::app()->user->setState('customer', null);
                        Yii::app()->user->setState('promocode', null);
                    }
                    //save to payment
                    if ((int)Yii::app()->config->get('use_initial_capital') > 0) {
                        $model5 = new Payment;
                        $model5->invoice_id = $model2->id;
                        $model5->amount_tendered = $this->money_unformat($_POST['PaymentForm']['amount_tendered']);
                        $model5->amount_change = $this->money_unformat($_POST['PaymentForm']['change']);
                        $model5->payment_session_id = PaymentSession::getSession(md5(date("Y-m-d")))->id;
                        $model5->date_entry = date(c);
                        $model5->user_entry = Yii::app()->user->id;
                        $model5->save();
                    }
                    //add queue for analytics
                    if (Order::hasAnalyticConfig()) {
                        $queue = new Queue;
                        $queue->invoice_id = $invoice_id;
                        $queue->date_entry = date(c);
                        $queue->user_entry = Yii::app()->user->id;
                        $queue->save();
                    }

                    echo CJSON::encode(array(
                        'status' => 'success',
                        'invoice_id' => $invoice_id,
                    ));
                    exit;
                }
            }
            echo CJSON::encode(array(
                'status' => 'success',
                'div' => $this->renderPartial('_payment', array('model' => $model, 'id' => $_POST['id']), true, true),
            ));
            exit;
        }
    }

    public function actionChangeRequest()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = true;
            $change = $this->money_unformat($_POST['amount_tendered']) - $this->getTotalBelanja();
            $model = new PaymentForm;
            Yii::app()->user->setState(
                'items_payment',
                array(
                    'amount_tendered' => $this->money_unformat($_POST['amount_tendered']),
                    'change' => $change,
                )
            );

            echo CJSON::encode(array(
                'status' => ($change >= 0) ? 'success' : 'failed',
                'div' => ($change >= 0) ? $this->renderPartial('_change', array('model' => $model, 'change' => $change), true, true) : 'Not enough tendered !',
            ));
            exit;
        }
    }

    public function actionDeleteItem()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            if (Yii::app()->user->hasState('items_belanja')) {
                $items = array();
                foreach (Yii::app()->user->getState('items_belanja') as $index => $data) {
                    if (!($index == $_POST['id']))
                        $items[$index] = $data;
                }
                Yii::app()->user->setState('items_belanja', $items);

                echo CJSON::encode(array(
                    'status' => 'success',
                    'div' => $this->renderPartial('_items', null, true, true),
                    'subtotal' => number_format($this->getTotalBelanja(), 0, ',', '.'),
                    'count' => count($items),
                ));
                exit;
            }
        }
    }

    public function actionCancelTransaction()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            if (Yii::app()->user->hasState('items_belanja')) {
                Yii::app()->user->setState('items_belanja', null);
                Yii::app()->user->setState('customer', null);
                Yii::app()->user->setState('promocode', null);
                Yii::app()->user->setState('transaction_type', null);

                echo CJSON::encode(array(
                    'status' => 'success',
                    'div' => $this->renderPartial('_items', null, true, true),
                    'subtotal' => number_format($this->getTotalBelanja(), 0, ',', '.'),
                ));
                exit;
            }
        }
    }

    public function actionViewItems()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

            $criteria = new CDbCriteria;

            if (Yii::app()->user->hasState('items_filter') & !isset($_POST['Product']))
                $_POST = Yii::app()->user->getState('items_filter');

            if (isset($_POST['Product'])) {
                $criteria->compare('barcode', $_POST['Product']['barcode'], true);
                $criteria->compare('LOWER(name)', strtolower($_POST['items_name']), true);
                Yii::app()->user->setState('items_filter', $_POST);
            }

            $dataProvider = new CActiveDataProvider('Product', array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => 10,
                    'pageVar' => 'page',
                    'currentPage' => $_GET['page'] - 1,
                )
            ));

            echo CJSON::encode(array(
                'status' => 'success',
                'div' => $this->renderPartial('_view_items', array('dataProvider' => $dataProvider), true, true),
            ));
            exit;
        }
    }

    public function actionPrint()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

            $amount_tendered = $this->money_unformat($_POST['amount_tendered']);
            $change = $amount_tendered - $this->getTotalBelanja();

            echo CJSON::encode(array(
                'status' => 'success',
                'div' => $this->renderPartial('_print', array('amount_tendered' => $amount_tendered, 'change' => $change), true, true),
            ));
            exit;
        }
    }

    public function actionPromocode()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

            $criteria = new CDbCriteria;
            $criteria->compare('active', 1);
            $criteria->compare('code', $_POST['promocode']);

            $model = Promo::model()->find($criteria);
            if (!empty($model->id)) {
                if (!empty($model->end_date)) {
                    if (strtotime($model->end_date) <= time())
                        Yii::app()->user->setState('promocode', $model->id);
                    else
                        Yii::app()->user->setState('promocode', null);
                } else
                    Yii::app()->user->setState('promocode', $model->id);

                if (Yii::app()->user->hasState('items_belanja')) {
                    $items = array();
                    foreach (Yii::app()->user->getState('items_belanja') as $index => $data) {
                        $data['discount'] = Promo::getDiscountValue($model->id, $data['unit_price']);
                        $items[$index] = $data;
                    }
                    Yii::app()->user->setState('items_belanja', $items);
                    if (Yii::app()->user->hasState('promocode')) {
                        echo CJSON::encode(array(
                            'status' => 'success',
                            'div' => Yii::t('order', 'Promo Code succesfully apllied.'),
                            'cart' => $this->renderPartial('_items', null, true, true),
                            'subtotal' => number_format($this->getTotalBelanja(), 0, ',', '.'),
                        ));
                    } else {
                        echo CJSON::encode(array(
                            'status' => 'failed',
                            'div' => Yii::t('order', 'Could not found Promocode, or your promocode is expired'),
                        ));
                    }
                }
            } else {
                Yii::app()->user->setState('promocode', null);
                echo CJSON::encode(array(
                    'status' => 'failed',
                    'div' => Yii::t('order', 'Could not found Promocode, or your promocode is expired'),
                ));
            }
            exit;
        }
    }

    public function getTotalBelanja()
    {
        $num = 0;
        if (Yii::app()->user->hasState('items_belanja')) {
            $items_belanja = Yii::app()->user->getState('items_belanja');
            foreach ($items_belanja as $index => $data) {
                $num = $num + ($data['unit_price'] * $data['qty']) - $data['discount'];
            }
        }
        return $num;
    }

    public function actionView()
    {
        $this->layout = 'column2';
        $criteria1 = new CDbCriteria;

        // for product tab
        $sql = 'SELECT t.title, SUM(t.quantity) AS quantity, SUM(t.price*t.quantity) AS tot_price, 
            AVG (t.cost_price) AS average_cost_price, t.date_entry AS last_ordered, 
             SUM((t.price-t.cost_price)*t.quantity) AS net_income, 
             SUM(t.cost_price*t.quantity) AS tot_cost 
            FROM tbl_order t 
            WHERE 1';

        $default_range = 'this_month';

        // for customer tab
        $sql3 = 'SELECT c.name AS customer_name, c.address AS customer_address, 
            SUM(t.quantity) AS total_quantity, 
            SUM(t.price*t.quantity) AS total_price,
            t.invoice_id      
            FROM tbl_order t 
            LEFT JOIN tbl_customer c ON c.id = t.customer_id 
            WHERE 1';

        if (isset($_GET['Order'])) {
            $criteria1->compare('customer_id', $_GET['Order']['customer_id']);
            $criteria1->compare('id', $_GET['Order']['id']);

            if (!empty($_GET['Order']['date_from']) && !empty($_GET['Order']['date_to'])) {
                $criteria1->addBetweenCondition('DATE_FORMAT(date_entry,"%Y-%m-%d")', $_GET['Order']['date_from'], $_GET['Order']['date_to'], 'AND');
                $sql .= ' AND DATE_FORMAT(t.date_entry,"%Y-%m-%d") BETWEEN "' . $_GET['Order']['date_from'] . '" AND "' . $_GET['Order']['date_to'] . '"';
                $default_range = null;
            }

            if (isset($_GET['Order']['range'])) {
                $default_range = $_GET['Order']['range'];
                switch ($_GET['Order']['range']) {
                    case "today":
                        $criteria1->addInCondition('DATE_FORMAT(date_entry, "%Y-%m-%d")', array(date("Y-m-d")), 'AND');
                        $sql .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m-%d") = "' . date("Y-m-d") . '"';
                        $sql3 .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m-%d") = "' . date("Y-m-d") . '"';
                        break;
                    case "this_week":
                        $criteria1->addBetweenCondition(
                            'DATE_FORMAT(date_entry,"%Y-%m-%d")',
                            date('Y-m-d', strtotime('monday this week')),
                            date('Y-m-d', strtotime('sunday this week')),
                            'AND'
                        );
                        $sql .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m-%d") 
                            BETWEEN "' . date('Y-m-d', strtotime('monday this week')) . '" 
                            AND "' . date('Y-m-d', strtotime('sunday this week')) . '"';
                        $sql3 .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m-%d") 
                            BETWEEN "' . date('Y-m-d', strtotime('monday this week')) . '" 
                            AND "' . date('Y-m-d', strtotime('sunday this week')) . '"';
                        break;
                    case "last_week":
                        $criteria1->addBetweenCondition(
                            'DATE_FORMAT(date_entry,"%Y-%m-%d")',
                            date('Y-m-d', strtotime('last week monday')),
                            date('Y-m-d', strtotime('last week sunday')),
                            'AND'
                        );
                        $sql .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m-%d") 
                            BETWEEN "' . date('Y-m-d', strtotime('last week monday')) . '" 
                            AND "' . date('Y-m-d', strtotime('last week sunday')) . '"';
                        $sql3 .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m-%d") 
                            BETWEEN "' . date('Y-m-d', strtotime('last week monday')) . '" 
                            AND "' . date('Y-m-d', strtotime('last week sunday')) . '"';
                        break;
                    case "this_month":
                        $criteria1->addInCondition('DATE_FORMAT(date_entry, "%Y-%m")', array(date("Y-m")), 'AND');
                        $sql .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m") = "' . date("Y-m") . '"';
                        $sql3 .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m") = "' . date("Y-m") . '"';
                        break;
                    case "last_month":
                        $criteria1->addInCondition('DATE_FORMAT(date_entry, "%Y-%m")', array(date('Y-m', strtotime(date('Y-m') . " -1 month"))), 'AND');
                        $sql .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m") = "' . date('Y-m', strtotime(date('Y-m') . " -1 month")) . '"';
                        $sql3 .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m") = "' . date('Y-m', strtotime(date('Y-m') . " -1 month")) . '"';
                        break;
                    case "this_year":
                        $criteria1->addInCondition('DATE_FORMAT(date_entry, "%Y")', array(date("Y")), 'AND');
                        $sql .= ' AND DATE_FORMAT(t.date_entry, "%Y") = "' . date('Y') . '"';
                        $sql3 .= ' AND DATE_FORMAT(t.date_entry, "%Y") = "' . date('Y') . '"';
                        break;
                }
            }
        } else {
            if (!empty($default_range)) {
                $criteria1->addInCondition('DATE_FORMAT(date_entry, "%Y-%m")', array(date("Y-m")), 'AND');
                $sql .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m") = "' . date("Y-m") . '"';
                $sql3 .= ' AND DATE_FORMAT(t.date_entry, "%Y-%m") = "' . date("Y-m") . '"';
            }
        }

        $criteria1->order = 'date_entry DESC';
        $dataProvider = new CActiveDataProvider(
            'Order',
            array(
                'criteria' => $criteria1,
                'pagination' => array('pageSize' => 100)
            ));
        $dataProvider->model->range = $default_range;

        $sql .= ' GROUP BY t.product_id ORDER BY quantity DESC';

        $productData = Yii::app()->db2->createCommand($sql)->queryAll();

        $sql2 = 'SELECT SUM(product_query.quantity) AS quantity,
          SUM(product_query.tot_price) AS tot_price,
          SUM(product_query.average_cost_price) AS average_cost_price,
          SUM(product_query.net_income) AS net_income, 
          SUM(product_query.tot_cost) AS tot_cost
          FROM (' . $sql . ') AS product_query';

        $productTotal = Yii::app()->db2->createCommand($sql2)->queryRow();

        $productProvider = new CArrayDataProvider($productData, array(
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        // for customer provider
        $sql3 .= ' GROUP BY t.customer_id ORDER BY total_price DESC';

        $customerData = Yii::app()->db2->createCommand($sql3)->queryAll();

        $customerProvider = new CArrayDataProvider($customerData, array(
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $this->render('view', array(
            'dataProvider' => $dataProvider,
            'productProvider' => $productProvider,
            'productTotal' => $productTotal,
            'customerProvider' => $customerProvider
        ));
    }

    public function actionUpdate($id)
    {
        $model = Order::model()->findByPk($id);
        if (isset($_POST['Order'])) {
            $model->attributes = $_POST['Order'];
            $model->date_update = date(c);
            $model->user_update = Yii::app()->user->id;
            if ($model->save()) {
                Yii::app()->user->setFlash('update', Yii::t('global', 'Your data has been saved successfully.'));
                $this->refresh();
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionChange($id)
    {
        $model = Invoice::model()->findByPk($id);
        $config = CJSON::decode($model->config);
        foreach ($config as $index => $data) {
            Yii::app()->user->setState($index, $data);
        }
        Yii::app()->user->setState('currency', $model->currency_id);
        $this->render('change', array(
            'model' => $model,
            'promocode' => Yii::app()->user->getState('promocode'),
            'customer' => $config['customer']['id'] . ' - ' . $config['customer']['name']
        ));
    }

    public function actionCreateDiscount()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            if (Yii::app()->user->hasState('items_belanja')) {
                $items_belanja = Yii::app()->user->getState('items_belanja');
                $id = $_POST['id'];
                $items_belanja[$id]['discount'] = $this->money_unformat($_POST['value']);
                //$items_belanja[$id]['qty']=2;
                //renew cart
                Yii::app()->user->setState('items_belanja', $items_belanja);
                echo CJSON::encode(array(
                    'status' => 'success',
                ));
                exit;
            }
        }
    }

    public function actionSetCurrency()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            if (Yii::app()->user->hasState('items_belanja')) {
                $items_belanja = Yii::app()->user->getState('items_belanja');
                Yii::app()->user->setState('currency', $_POST['value']);
                foreach ($items_belanja as $index => $data) {
                    $product = Product::model()->findByPk($data['id']);
                    $items_belanja[$index]['currency'] = $_POST['value'];
                    $items_belanja[$index]['change_value'] = Currency::getChangeValue($_POST['value']);
                    if (Currency::getChangeValue($_POST['value']) > 0) {
                        $items_belanja[$index]['unit_price'] = round($product->price->sold_price / Currency::getChangeValue($_POST['value']), 2);
                        $items_belanja[$index]['discount'] = round($data['discount'] / Currency::getChangeValue($_POST['value']), 2);
                    }
                }
                Yii::app()->user->setState('items_belanja', $items_belanja);
                echo CJSON::encode(array(
                    'status' => 'success',
                ));
                exit;
            }
        }
    }

    public function actionPaymentRequestUpdate($id)
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

            $model = new PaymentForm;
            if (isset($_POST['PaymentForm'])) {
                if (Yii::app()->user->hasState('items_belanja')) {
                    $model2 = Invoice::model()->findByPk($id);
                    if (Yii::app()->user->hasState('customer')) {
                        $customer = Yii::app()->user->getState('customer');
                        $model2->customer_id = (!empty($customer)) ? $customer->id : 0;
                    }
                    $model2->status = 1;
                    $model2->config = CJSON::encode(
                        array(
                            'items_belanja' => Yii::app()->user->getState('items_belanja'),
                            'items_payment' => Yii::app()->user->getState('items_payment'),
                            'customer' => Yii::app()->user->getState('customer'),
                            'promocode' => Yii::app()->user->getState('promocode'),
                            'transaction_type' => Yii::app()->user->getState('transaction_type')
                        )
                    );
                    $model2->currency_id = Yii::app()->user->getState('currency');
                    $model2->change_value = Currency::getChangeValue($model2->currency_id);
                    if (!empty($_POST['PaymentForm']['notes'])) {
                        $model2->notes = $_POST['PaymentForm']['notes'];
                    }
                    $model2->date_update = date(c);
                    $model2->user_update = Yii::app()->user->id;
                    if ($model2->save()) {
                        $invoice_id = $model2->id;
                        $group_id = Order::getNextGroupId();
                        $del = Order::model()->deleteAllByAttributes(array('invoice_id' => $invoice_id));
                        $del2 = InvoiceItem::model()->deleteAllByAttributes(array('invoice_id' => $invoice_id));
                        foreach (Yii::app()->user->getState('items_belanja') as $index => $data) {
                            $model3 = new Order;
                            $model3->product_id = $data['id'];
                            $model3->customer_id = $model2->customer_id;
                            $product = Product::item($model3->product_id);
                            $model3->title = $product->name;
                            $model3->group_id = $group_id;
                            $model3->group_master = ($index == 0) ? 1 : 0;
                            $model3->invoice_id = $model2->id;
                            $model3->quantity = $data['qty'];
                            //$model3->price=$product->price->sold_price;
                            $model3->price = $data['unit_price'];
                            $model3->discount = $data['discount'];
                            if (Yii::app()->user->hasState('promocode')) {
                                $model3->promo_id = Yii::app()->user->getState('promocode');
                                $model3->discount = Promo::getDiscountValue(Yii::app()->user->getState('promocode'), $model3->price);
                            }
                            $model3->currency_id = $model2->currency_id;
                            $model3->change_value = $model2->change_value;
                            $model3->type = $_POST['PaymentForm']['type'];
                            $model3->status = 1;
                            $model3->date_entry = date(c);
                            $model3->user_entry = Yii::app()->user->id;
                            if ($model3->save()) {
                                $model4 = new InvoiceItem;
                                $model4->invoice_id = $model2->id;
                                $model4->type = 'order';
                                $model4->rel_id = $model3->id;
                                $model4->title = $model3->title;
                                $model4->quantity = $model3->quantity;
                                $model4->price = $model3->quantity * ($model3->price - $model3->discount);
                                $model4->date_entry = date(c);
                                $model4->user_entry = Yii::app()->user->id;
                                $model4->save();
                            }
                        }
                        Yii::app()->user->setState('items_belanja', null);
                        Yii::app()->user->setState('items_payment', null);
                        Yii::app()->user->setState('customer', null);
                        Yii::app()->user->setState('promocode', null);
                        Yii::app()->user->setState('transaction_type', null);
                    }
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'invoice_id' => $invoice_id,
                    ));
                    exit;
                }
            }
        }
    }

    public function actionListCustomer()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

            $customer_model = new Customer();
            $customers = $customer_model->list_items('Pilih Pelanggan');

            echo CJSON::encode(array(
                'status' => 'success',
                'div' => $this->renderPartial('_list_customer', array('items' => $customers, 'selected' => $customer_model->get_last_item()), true, true)
            ));
            exit;
        }
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            Order::model()->findByPk($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionSetTransactionType()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

            Yii::app()->user->setState('transaction_type', $_POST['value']);
            if (Yii::app()->user->hasState('items_belanja')) {
                $items_belanja = Yii::app()->user->getState('items_belanja');
                foreach ($items_belanja as $index => $data) {
                    if ($_POST['value'] == Invoice::STATUS_PAID) {
                        if ($data['unit_price'] < 0) {
                            $items_belanja[$index]['unit_price'] = -1 * $data['unit_price'];
                        }
                    } elseif ($_POST['value'] == Invoice::STATUS_REFUND) {
                        if ($data['unit_price'] > 0) {
                            $items_belanja[$index]['unit_price'] = -1 * $data['unit_price'];
                        }
                    }
                }
                Yii::app()->user->setState('items_belanja', $items_belanja);
            }

            echo CJSON::encode(array(
                'status' => 'success',
            ));
            exit;
        }
    }
}
