<?php

Yii::app()->moduleManager->register(array(
    'id' => 'birthday',
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
