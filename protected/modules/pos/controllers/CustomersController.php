<?php

class CustomersController extends EController
{
    public static $_alias = 'Manage Customer';

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
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
                'actions' => array('view'),
                'expression' => 'Rbac::ruleAccess(\'read_p\')',
            ),
            array('allow',
                'actions' => array('create'),
                'expression' => 'Rbac::ruleAccess(\'create_p\')',
            ),
            array('allow',
                'actions' => array('update'),
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Customer;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Customer'])) {
            $model->attributes = $_POST['Customer'];
            $model->name = $_POST['Customer']['name'];
            $model->email = $_POST['Customer']['email'];
            $model->address = $_POST['Customer']['address'];
            $model->telephone = $_POST['Customer']['telephone'];
            $model->status = 1;
            $model->date_entry = date(c);
            $model->user_entry = Yii::app()->user->id;
            if ($model->save()) {
                Yii::app()->user->setFlash('create', Yii::t('global', 'Your data has been saved successfully.'));
                $this->refresh();
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Customer'])) {
            $model->attributes = $_POST['Customer'];
            $model->name = $_POST['Customer']['name'];
            $model->email = $_POST['Customer']['email'];
            $model->address = $_POST['Customer']['address'];
            $model->telephone = $_POST['Customer']['telephone'];
            $model->date_update = date(c);
            $model->user_update = Yii::app()->user->id;
            if ($model->save()) {
                Yii::app()->user->setFlash('update', Yii::t('global', 'Your data has been saved successfully.'));
                $this->refresh();
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionView()
    {
        $model = new Customer('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Customer']))
            $model->attributes = $_GET['Customer'];

        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Customer::model()->findByPk((int)$id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'promo-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
