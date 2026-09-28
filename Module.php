<?php

namespace humhub\modules\birthday;

use yii\helpers\Url;

/**
 * BirthdayModule is responsible for the birthday functions.
 *
 * @author Sebastian Stumpf
 */
class Module extends \humhub\components\Module
{
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
