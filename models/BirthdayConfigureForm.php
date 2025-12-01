<?php

namespace humhub\modules\birthday\models;

use humhub\modules\birthday\Module;
use Yii;
use yii\base\Model;

/**
 * BirthdayConfigureForm defines the configurable fields.
 *
 * @package humhub.modules.birthday.forms
 * @author Sebastian Stumpf
 */
class BirthdayConfigureForm extends Model
{
    public $shownDays;
    public $excludedGroup;

    public ?Module $module = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->module = Yii::$app->getModule('birthday');

        $this->shownDays = $this->module->settings->get('shownDays');
        $this->excludedGroup = $this->module->settings->get('excludedGroup');
    }

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['shownDays', 'required'],
            ['shownDays', 'integer', 'min' => 0, 'max' => 90],
            ['excludedGroup', 'integer', 'min' => 1, 'max' => 1000000],
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
        ];
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $this->module->settings->set('shownDays', $this->shownDays);
        $this->module->settings->set('excludedGroup', $this->excludedGroup);

        return true;
    }
}
