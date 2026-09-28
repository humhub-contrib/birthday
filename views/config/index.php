<?php

use humhub\modules\birthday\models\BirthdayConfigureForm;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\form\ActiveForm;

/* @var $model BirthdayConfigureForm */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('BirthdayModule.base', 'Birthday Module Configuration'); ?>
    </div>
    <div class="panel-body">
        <p><?= Yii::t('BirthdayModule.base', 'You may configure the number of days within the upcoming birthdays are shown.'); ?></p>
        <br>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'shownDays')->textInput() ?>

        <?= $form->field($model, 'excludedGroup')->textInput() ?>

        <?= $form->field($model, 'sidebarSortOrder')->textInput(['type' => 'number']) ?>

        <hr>
        <?= Button::save()->submit() ?>
        <?= Button::light(Yii::t('BirthdayModule.base', 'Back to modules'))
            ->link(['/admin/module'])
            ->right() ?>
        <?php $form::end(); ?>
    </div>
</div>
