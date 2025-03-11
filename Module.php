<?php

namespace humhub\modules\birthday;

use Yii;
use yii\helpers\Url;
use humhub\components\Module as BaseModule;
/**
 * BirthdayModule is responsible for the birthday functions.
 */
class Module extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $resourcesPath = 'resources';

    /**
     * @var int The sort order for the birthdays sidebar widget.
     */
    public $sidebarSortOrder = 200;

    /**
     * On build of the dashboard sidebar widget, add the birthday widget if the module is enabled.
     *
     * @param \yii\base\Event $event
     */
    public static function onSidebarInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $module = Yii::$app->getModule('birthday');
        $event->sender->addWidget(widgets\BirthdaySidebarWidget::class, [], ['sortOrder' => $module->sidebarSortOrder]);
    }

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to(['/birthday/config']);
    }

    /**
     * @inheritdoc
     */
    public function enable()
    {
        parent::enable();
        $this->settings->set('shownDays', 2);
    }
}
