<?php

namespace humhub\modules\birthday\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\birthday\models\BirthdayConfigureForm;
use Yii;

/**
 * Defines the configure actions.
 *
 * @package humhub.modules.birthday.controllers
 * @author Sebastian Stumpf
 */
class ConfigController extends Controller
{
    /**
     * Configuration Action for Super Admins
     */
    public function actionIndex()
    {
        $form = new BirthdayConfigureForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            $this->view->saved();
            return $this->redirect(['/birthday/config']);
        }

        return $this->render('index', ['model' => $form]);
    }

}
