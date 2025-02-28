<?php

namespace humhub\modules\birthday\models;

use Yii;
use humhub\modules\birthday\Module;

/**
 * BirthdayConfigureForm defines the configurable fields.
 *
 * @package humhub.modules.birthday.forms
 * @author Sebastian Stumpf
 */
class BirthdayConfigureForm extends \yii\base\Model
{
    public $enabled;
    public $shownDays;
    public $excludedGroup;

    /**
     * @var Module
     */
    private $module;

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
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return [
            'shownDays' => Yii::t('BirthdayModule.base', 'The number of days future birthdays will be shown within.'),
            'excludedGroup' => Yii::t('BirthdayModule.base', 'The group id of the group that should be exluded.'),
            'enabled' => Yii::t('BirthdayModule.base', 'Disable modal on clicking users within birthday widget.'),
        ];
    }

    /**
     * Loads the current settings from database
     */
    public function loadSettings()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('birthday');
        $settings = $module->settings;

        $this->enabled = (boolean)$settings->get('enabled', false);
    }

    /**
     * Saves the form settings
     */
    public function save()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('birthday');
        $settings = $module->settings;

        $settings->set('enabled', (boolean)$this->enabled);

        return true;
    }
}