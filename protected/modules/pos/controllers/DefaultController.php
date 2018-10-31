<?php

class DefaultController extends EController
{
    public $layout = '//layouts/column1';

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
            array('allow',  // allow all users to access 'index' and 'view' actions.
                'actions' => array('logout', 'index', 'deleteItem', 'plot'),
                'users' => array('@'),
            ),
            array('allow',  // allow all users to access 'index' and 'view' actions.
                'actions' => array('error', 'login', 'cron'),
                'users' => array('*'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $this->layout = 'column2';
        $criteria = new CDbCriteria;
        $criteria->compare('DATE_FORMAT(date_entry, \'%Y-%m-%d\')', date("Y-m-d"));
        $criteria->group = 'product_id';
        $criteria->order = 'date_entry ASC';

        $dataProvider = new CActiveDataProvider('Order', array('criteria' => $criteria, 'pagination' => array('pageSize' => 20)));

        $this->render('index', array('dataProvider' => $dataProvider));
    }

    public function actionCron()
    {
        $oempro = new OemproMailingList;
        if (is_array($oempro->setting())) {
            foreach ($oempro->setting() as $type => $data) {
                $params = array_merge(array(
                    'type' => $type,
                ), $data);
                $this->execute($params);
            }
        }
        return true;
    }

    private function execute($params)
    {
        $criteria = new CDbCriteria;
        if (!empty($params['flag']))
            $criteria->compare($params['flag_column'], $params['flag']);
        $criteria->order = 'id DESC';
        $criteria->limit = 1;
        $model = $params['class']::model()->find($criteria);

        $criteria2 = new CDbCriteria;
        $criteria2->compare('type', $params['type']);
        $criteria2->order = 'rel_id DESC';
        $model2 = MailingList::model()->find($criteria2);
        if ($model2->rel_id < $model->id) {
            $criteria3 = new CDbCriteria;
            if (!empty($params['flag']))
                $criteria3->compare($params['flag_column'], $params['flag']);
            $criteria3->compare('id', '>' . (int)$model2->rel_id);
            $criteria3->order = 'id ASC';
            $criteria3->limit = 1;
            $datas = $params['class']::model()->findAll($criteria3);
            if (count($datas) > 0) {
                foreach ($datas as $data) {
                    $list = new MailingList;
                    $list->name = $data->name;
                    $list->email = $data->email;
                    $list->type = $params['type'];
                    $list->status = 'unsubscribe';
                    $list->rel_id = $data->id;
                    $list->created_at = date(c);
                    if ($list->save()) {
                        //do something here
                        $param = array(
                            'list' => $params['value'],
                            'email' => $data->email,
                            'name' => $data->name,
                            'custom_field' => $params['custom_field'],
                        );
                        $oempro = new OemproMailingList;
                        //$push=$oempro->addSubscriber($param);
                        $push = true;
                        //var_dump($push);exit;
                        $list->executed = 1;
                        if ($push) {
                            $list->status = 'subscribed';
                            $list->subscribed_at = date(c);
                            $list->update(array('executed', 'subscribed_at', 'status'));
                        } else
                            $list->update(array('executed'));
                    }
                }
            }
        }
        return true;
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $this->layout = 'column1';

        $model = new AdminLoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['AdminLoginForm'])) {
            $model->attributes = $_POST['AdminLoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                if (RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'orders', Yii::app()->user->id, 'create_p'))
                    $this->redirect(array('orders/create'));
                else
                    $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(array('default/index'));
    }

    public function actionDeleteItem($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionPlot()
    {
        if (Yii::app()->request->isAjaxRequest) {
            echo CJSON::encode(array(
                'status' => 'success',
                'income' => json_encode(Order::getStatistikMonthly('income')),
            ));
        }
    }

    public function loadModel($id)
    {
        $model = MailingList::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionError()
    {
        $this->layout = 'column1';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
}
