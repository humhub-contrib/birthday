<?php

use humhub\widgets\form\ActiveForm ;
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\widgets\bootstrap\Button;

/**
 * @var $model \humhub\modules\birthday\models\BirthdayConfigureForm
 */

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('BirthdayModule.base', 'Birthday Module Configuration'); ?>
    </div>
    <div class="panel-body">
        <p><?= Yii::t('BirthdayModule.base', 'You may configure the number of days within the upcoming birthdays are shown.'); ?></p>
        <br/>

        <?php $form = ActiveForm::begin(); ?>

        <div class="mb-3">
            <?php echo $form->field($model, 'shownDays')->textInput(); ?>
        </div>

        <div class="mb-3">
            <?php echo $form->field($model, 'excludedGroup')->textInput(); ?>
        </div>

        <hr>
        <?= Button::save()->submit() ?>
        <?= Button::light(Yii::t('BirthdayModule.base', 'Back to modules'))
                ->link(Url::to(['/admin/module']))
                ->cssClass('float-end') ?>
        <?php $form::end(); ?>
    </div>
</div>
