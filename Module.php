<?php

namespace humhub\modules\birthday;

use Yii;
use humhub\modules\birthday\widgets\BirthdaySidebarWidget;
use humhub\models\Setting;
use yii\helpers\Url;

/**
 * BirthdayModule is responsible for the the birthday functions.
 * 
 * @author Sebastian Stumpf
 *
 */
class Module extends \humhub\components\Module
{

    /**
     * On build of the dashboard sidebar widget, add the birthday widget if module is enabled.
     *
     * @param type $event
     */
    public static function onSidebarInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $event->sender->addWidget(BirthdaySidebarWidget::className(), array(), array('sortOrder' => 200));
    }

    public function getConfigUrl()
    {
        return Url::to(['/birthday/config']);
    }

    /**
     * Enables this module
     */
    public function enable()
    {
        Setting::Set('shownDays', 2, 'birthday');
        return parent::enable();
    }

}

?>
