<?php

use humhub\modules\ui\form\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

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

        <div class="form-group">
            <?php echo $form->field($model, 'shownDays')->textInput(); ?>
        </div>

        <div class="form-group">
            <?php echo $form->field($model, 'excludedGroup')->textInput(); ?>
        </div>

        <hr>
        <?= Html::submitButton(Yii::t('BirthdayModule.base', 'Save'), ['class' => 'btn btn-primary']); ?>
        <a class="btn btn-default" href="<?= Url::to(['/admin/module']); ?>">
            <?= Yii::t('BirthdayModule.base', 'Back to modules'); ?>
        </a>

        <?php $form::end(); ?>
    </div>
</div>
