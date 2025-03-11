<?php

namespace humhub\modules\birthday\models;

use Yii;
use yii\base\Model;
use humhub\modules\birthday\Module;

/**
 * BirthdayConfigureForm defines the configurable fields.
 *
 * @package humhub.modules.birthday.forms
 * @author Sebastian Stumpf
 */
class BirthdayConfigureForm extends Model
{
    public $enabled;
    public $shownDays;
    public $excludedGroup;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['shownDays', 'required'],
            ['shownDays', 'integer', 'min' => 0, 'max' => 90],
            ['excludedGroup', 'integer', 'min' => 1, 'max' => 1000000],
            ['enabled', 'boolean'],
        ];
    }

    /**
     * Declares customized attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'shownDays' => Yii::t('BirthdayModule.base', 'The number of days future birthdays will be shown within.'),
            'excludedGroup' => Yii::t('BirthdayModule.base', 'The group id of the group that should be excluded.'),
            'enabled' => Yii::t('BirthdayModule.base', 'Disable modal on clicking users within birthday widget.'),
        ];
    }

    /**
     * Loads the current settings from database
     */
    public function loadSettings()
    {
        $module = Yii::$app->getModule('birthday');
        $settings = $module->settings;

        $this->enabled = (bool) $settings->get('enabled', false);
        $this->shownDays = (int) $settings->get('shownDays', 7);
        $this->excludedGroup = (int) $settings->get('excludedGroup', null);
    }

    /**
     * Saves the form settings
     */
    public function save()
    {
        $module = Yii::$app->getModule('birthday');
        $settings = $module->settings;

        $settings->set('enabled', (bool) $this->enabled);
        $settings->set('shownDays', (int) $this->shownDays);
        $settings->set('excludedGroup', (int) $this->excludedGroup);

        return true;
    }
}
