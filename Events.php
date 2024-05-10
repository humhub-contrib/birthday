<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\birthday;

use humhub\modules\birthday\widgets\BirthdaySidebarWidget;
use humhub\modules\dashboard\widgets\Sidebar;
use Yii;
use yii\base\Event;

class Events
{
    /**
     * On build of the dashboard sidebar widget, add the birthday widget if module is enabled.
     *
     * @param Event $event
     */
    public static function onDashboardSidebarInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        /* @var Module $module */
        $module = Yii::$app->getModule('birthday');

        /* @var Sidebar $sidebar */
        $sidebar = $event->sender;
        $sidebar->addWidget(BirthdaySidebarWidget::class, [], ['sortOrder' => $module->sidebarSortOrder]);
    }
}
