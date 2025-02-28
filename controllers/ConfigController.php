<?php

namespace humhub\modules\birthday\controllers;

use Yii;
use humhub\modules\admin\components\Controller;
use humhub\modules\birthday\models\BirthdayConfigureForm;

/**
 * Admin configuration controller for the Birthday module.
 */
class ConfigController extends Controller
{
    /**
     * Displays and processes the Birthday module settings form.
     */
    public function actionIndex()
    {
        $model = new BirthdayConfigureForm();
        $model->loadSettings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->view->saved();
        }

        return $this->render('index', ['model' => $model]);
    }
}