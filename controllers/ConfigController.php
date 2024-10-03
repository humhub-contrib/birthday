<?php

namespace humhub\modules\birthday\controllers;

use Yii;
use humhub\models\Setting;

/**
 * Defines the configure actions.
 *
 * @package humhub.modules.birthday.controllers
 * @author Sebastian Stumpf
 */
class ConfigController extends \humhub\modules\admin\components\Controller
{
    /**
     * Configuration Action for Super Admins
     */
    public function actionIndex()
    {
        $form = new \humhub\modules\birthday\models\BirthdayConfigureForm();
        $form->shownDays = Setting::Get('shownDays', 'birthday');
        $form->excludedGroup = Setting::Get('excludedGroup', 'birthday');
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->shownDays = Setting::Set('shownDays', $form->shownDays, 'birthday');
            $form->excludedGroup = Setting::Set('excludedGroup', $form->excludedGroup, 'birthday');
            return $this->redirect(['/birthday/config']);
        }

        return $this->render('index', ['model' => $form]);
    }

}
