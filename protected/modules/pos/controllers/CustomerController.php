<?php

class CustomerController extends EController
{
    public $layout = 'column1';
    public static $_alias = 'Add Customer';

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

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
                'actions' => array('create', 'view', 'choose', 'suggestCustomers'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionCreate()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $model = new Customer;
            if (isset($_POST['Customer'])) {
                $model->attributes = $_POST['Customer'];
                $model->status = 1;
                $model->date_entry = date(c);
                $model->user_entry = Yii::app()->user->id;
                if ($model->save()) {
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => '<div class="alert alert-success">' . Yii::t('global', 'Your data is succesfully saved.') . '</div>',
                    ));
                } else {
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => $this->renderPartial('_form', array('model' => $model), true, true),
                    ));
                }
            } else {
                echo CJSON::encode(array(
                    'status' => 'success',
                    'div' => $this->renderPartial('_form', array('model' => $model), true, true),
                ));
            }
        }
    }

    public function actionView()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
            $criteria = new CDbCriteria;
            $dataProvider = new CActiveDataProvider('Customer', array('criteria' => $criteria));
            if (isset($_POST['Customer'])) {
                $criteria->compare('name', $_POST['Customer']['name'], true);
                $dataProvider->setCriteria($criteria);
                echo CJSON::encode(array(
                    'status' => 'success',
                    'div' => $this->renderPartial('view', array('dataProvider' => $dataProvider), true, true),
                ));
            } else {

                echo CJSON::encode(array(
                    'status' => 'success',
                    'div' => $this->renderPartial('view', array('dataProvider' => $dataProvider), true, true),
                ));
            }
        }
    }

    public function actionChoose()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
            if (isset($_POST['id']))
                $id = $_POST['id'];
            elseif (isset($_POST['nama'])) {
                $str = explode(" - ", $_POST['nama']);
                $id = (int)$str[0];
            }

            $model = Customer::model()->findByPk($id);
            if (count($model) > 0) {
                Yii::app()->user->setState('customer', $model);
                echo CJSON::encode(array('status' => 'success', 'div' => $model->name));
            }
        }
    }

    public function actionSuggestCustomers()
    {
        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {

            $criteria = new CDbCriteria;
            $criteria->compare('LOWER(t.name)', strtolower($keyword), true);
            $criteria->limit = 10;

            $models = Customer::model()->findAll($criteria);

            $items = array();
            foreach ($models as $model) {
                $items[] = $model->id . ' - ' . $model->name;
            }

            if (is_array($items))
                echo implode("\n", $items);
        }
    }
}
