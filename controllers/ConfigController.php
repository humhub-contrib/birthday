<?php
/**
 * Defines the configure actions.
 *
 * @package humhub.modules.birthday.controllers
 * @author Sebastian Stumpf
 */
class ConfigController extends Controller {

    public $subLayout = "application.modules_core.admin.views._layout";

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'expression' => 'Yii::app()->user->isAdmin()',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }


    /**
     * Configuration Action for Super Admins
     */
    public function actionConfig() {

        Yii::import('birthday.forms.*');

        $form = new BirthdayConfigureForm();

        // uncomment the following code to enable ajax-based validation
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'notes-configure-form') {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }

        if (isset($_POST['BirthdayConfigureForm'])) {
            $_POST['BirthdayConfigureForm'] = Yii::app()->input->stripClean($_POST['BirthdayConfigureForm']);
            $form->attributes = $_POST['BirthdayConfigureForm'];

            if ($form->validate()) {

                $form->shownDays = HSetting::Set('shownDays', $form->shownDays, 'birthday');
                $this->redirect(Yii::app()->createUrl('birthday/config/config'));
            }
        } else {
            $form->shownDays = HSetting::Get('shownDays', 'birthday');
            if($form->shownDays = '') {
            	$form->shownDays = 0;
            }
        }

        $this->render('config', array('model' => $form));
    }
}

?>
