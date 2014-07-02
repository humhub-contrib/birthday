
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('BirthdayModule.base', 'Birthday Module Configuration'); ?></div>
    <div class="panel-body">


        <p><?php echo Yii::t('BirthdayModule.base', 'You may configure the number of days within the upcoming birthdays are shown.'); ?></p>
        <br/>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'birthday-configure-form',
            'enableAjaxValidation' => true,
        ));
        ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'shownDays'); ?>
            <?php echo $form->numberField($model, 'shownDays', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'shownDays'); ?>
        </div>

        <hr>
        <?php echo CHtml::submitButton(Yii::t('BirthdayModule.base', 'Save'), array('class' => 'btn btn-primary')); ?>
        <a class="btn btn-default" href="<?php echo $this->createUrl('//admin/module'); ?>"><?php echo Yii::t('AdminModule.base', 'Back to modules'); ?></a>

        <?php $this->endWidget(); ?>
    </div>
</div>