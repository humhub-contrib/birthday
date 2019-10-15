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
 */
class Module extends \humhub\components\Module
{

    /**      *
     * @var int the sort order for the birthdays sidebar widget
     */
    public $sidebarSortOrder = 200;
    
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
        
        $module = Yii::$app->getModule('birthday');
        $event->sender->addWidget(BirthdaySidebarWidget::class, [], ['sortOrder' => $module->sidebarSortOrder]);
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
        Setting::Set('shownDays', 2, 'birthday');
    }

}

?>
