<?php

Yii::app()->moduleManager->register(array(
    'id' => 'birthday',
    'title' => Yii::t('BirthdayModule.base', 'Birthday Module'),
    'description' => Yii::t('BirthdayModule.base', 'Module showing today\'s and tomorrow\'s birthdays'),
    'class' => 'application.modules.birthday.BirthdayModule',
    'import' => array(
        'application.modules.birthday.*',
    ),
    // Events to Catch 
    'events' => array(
        array('class' => 'DashboardSidebarWidget', 'event' => 'onInit', 'callback' => array('BirthdayModule', 'onSidebarInit')),
    ),
));
?>
