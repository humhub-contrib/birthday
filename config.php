<?php

use humhub\modules\dashboard\widgets\Sidebar;

return [
    'id' => 'birthday',
    'class' => 'humhub\modules\birthday\Module',
    'namespace' => 'humhub\modules\birthday',
    'events' => [
        array('class' => Sidebar::className(), 'event' => Sidebar::EVENT_INIT, 'callback' => array('humhub\modules\birthday\Module', 'onSidebarInit')),
    ],
];
?>
