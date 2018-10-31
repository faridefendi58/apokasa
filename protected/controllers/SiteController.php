<?php

class SiteController extends DController
{
    public $layout = 'column1';

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
                'actions' => array('index', 'contact', 'captcha', 'search', 'error', 'slug', 'image', 'home', 'reservation'),
                'users' => array('*'),
            ),
            array('allow',  // allow all users to access 'index' and 'view' actions.
                'actions' => array('unitAvailable', 'requestAvailableUnit', 'requestKamar'),
                'users' => array('*'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        $this->layout = 'column_blank';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionIndex()
    {
        $this->layout = 'column1';
        $this->forward('/site/home');
        $this->render('index');
    }

    public function actionHome()
    {
        $this->layout = 'column_home';

        $criteria = new CDbCriteria;
        $criteria->compare('product_id', 1);
        $dataProvider = new CActiveDataProvider('ProductItems', array('criteria' => $criteria));

        $this->render('home', array('dataProvider' => $dataProvider));
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $this->layout = 'column_page';

        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $mail_var = array('{-nama-}' => $model->name, '{-email-}' => $model->email, '{-body-}' => $model->body);
                $this->sendMail($model->email, Yii::app()->config->get('admin_email'), $model->subject, $mail_var, 'email_contact_admin');
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    public function actionReservation()
    {
        $this->layout = 'column2';

        $this->render('reservation', array('model' => $model));
    }

    public function actionSearch()
    {
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['question'])) {
                // Stop jQuery from re-initialization
                Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                //Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
                //Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

                $criteria = new CDbCriteria;
                $criteria->compare('content_rel.title', $_POST['question'], true, 'OR');
                $criteria->compare('content_rel.content', $_POST['question'], true, 'OR');
                $criteria->compare('lang.code', Yii::app()->language);
                $criteria->with = array('content_rel', 'content_rel.language_rel' => array('alias' => 'lang'));
                $criteria->together = true;
                $dataProvider = new CActiveDataProvider('Post', array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 100,
                    ),
                ));
                //set state for search result
                Yii::app()->user->setState('Search_key', $_POST['question']);

                echo CJSON::encode(array(
                    'status' => 'success',
                    'div' => $this->renderPartial('search_result', array('dataProvider' => $dataProvider), true, true),
                ));
                exit;
            }
        } else {
            $model = new SearchEngineForm;
            if (isset($_POST['SearchEngineForm'])) {
                $model->attributes = $_POST['SearchEngineForm'];
                if ($model->validate()) {
                    $criteria = new CDbCriteria;
                    $criteria->compare('content', $model->search_for, true);
                    $criteria->compare('title', $model->search_for, true, 'OR');
                    $dataProvider = new CActiveDataProvider('Post', array('criteria' => $criteria));
                }
            }

            $this->render('search', array('model' => $model, 'dataProvider' => $dataProvider));
        }
    }

    public function actionSlug()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('slug', '');
        $models = Post::model()->findAll($criteria);
        $items = array();
        if (count($models) > 0) {
            foreach ($models as $model) {
                $title = $model->createSlug();
                $model->slug = $title;
                if ($model->update('slug'))
                    $items[] = $model->slug;
            }
        }
        var_dump($items);
        exit;
    }

    public function actionImage($key)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('t.key', $key);
        $model = Banner::model()->find($criteria);
        if (@file_exists($model->src)) {
            $path_parts = @pathinfo($model->src);
            $filename = basename($model->src);
            $file_extension = strtolower(substr(strrchr($filename, "."), 1));
            switch ($file_extension) {
                case "gif":
                    $ctype = "image/gif";
                    $im = imagecreatefromgif($model->src);
                    break;
                case "png":
                    $ctype = "image/png";
                    $im = imagecreatefrompng($model->src);
                    $background = imagecolorallocate($im, 0, 0, 0);
                    imagecolortransparent($im, $background);
                    imagealphablending($im, false);
                    imagesavealpha($im, true);
                    break;
                case "jpeg":
                case "jpg":
                    $ctype = "image/jpg";
                    $im = imagecreatefromjpeg($model->src);
                    break;
                default:
            }

            header('Content-Type: ' . $ctype);
            imagepng($im);
            imagedestroy($im);
        }
    }

    public function actionUnitAvailable()
    {
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $criteria = new CDbCriteria;
            $criteria->compare('product_id', $_POST['product_id']);
            $dataProvider = new CActiveDataProvider('ProductItems', array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => 10,
                ),
            ));
            echo CJSON::encode(array(
                'status' => 'success',
                'div' => $this->renderPartial('unit_available', array('dataProvider' => $dataProvider), true, true),
            ));
            exit;
        }
    }

    public function actionRequestAvailableUnit()
    {
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            if (ProductAvailability::get_availability($_POST['product_item_id'])) {
                echo CJSON::encode(array(
                    'status' => 'success',
                    'div' => $this->renderPartial('unit_available_check', array('product_item_id' => $_POST['product_item_id']), true, true),
                ));
            } else {
                echo CJSON::encode(array(
                    'status' => 'failed',
                    'div' => '<b>Tidak ditemukan unit yang masih tersedia.</b>',
                ));
            }
            exit;
        }
    }

    public function actionRequestKamar()
    {
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $product_item = ProductItems::model()->findByPk($_POST['product_item_id']);
            $image = ProductImages::getImageByType($_POST['product_item_id'], 3);
            echo CJSON::encode(array(
                'status' => 'success',
                'div' => CHtml::image(Yii::app()->request->baseUrl . '/' . $image->src . $image->image, '', array('class' => 'img-responsive')),
                'link' => CHtml::link('Pesan Sekarang', array('/pemesanan?product=' . $product_item->product_id . '&unit=' . $product_item->id), array('class' => 'btn btn-primary', 'style' => 'min-width:120px;')),
            ));
            exit;
        }
    }
}
