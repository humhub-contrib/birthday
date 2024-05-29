<?php

namespace humhub\modules\birthday;

use humhub\models\Setting;
use yii\helpers\Url;

/**
 * BirthdayModule is responsible for the the birthday functions.
 *
 * @author Sebastian Stumpf
 */
class Module extends \humhub\components\Module
{
    /**
     * @var int the sort order for the birthdays sidebar widget
     */
    public int $sidebarSortOrder = 200;

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
