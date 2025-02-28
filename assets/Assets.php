<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\birthday\assets;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    public $sourcePath = '@birthday/resources';

    public $css = [
        'css/birthday.css',
    ];
}