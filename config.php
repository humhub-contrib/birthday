<?php

use humhub\modules\birthday\Events;
use humhub\modules\dashboard\widgets\Sidebar;

return [
    'id' => 'birthday',
    'class' => 'humhub\modules\birthday\Module',
    'namespace' => 'humhub\modules\birthday',
    'events' => [
        ['class' => Sidebar::class, 'event' => Sidebar::EVENT_INIT, 'callback' => [Events::class, 'onDashboardSidebarInit']],
    ],
];
