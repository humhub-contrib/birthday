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
    public $sidebarSortOrder;

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
        $this->sidebarSortOrder = $this->module->settings->get('sidebarSortOrder', 200);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shownDays', 'sidebarSortOrder'], 'required'],
            ['shownDays', 'integer', 'min' => 0, 'max' => 90],
            ['excludedGroup', 'integer', 'min' => 1, 'max' => 1000000],
            ['sidebarSortOrder', 'integer'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shownDays' => Yii::t('BirthdayModule.base', 'The number of days future birthdays will be shown within.'),
            'excludedGroup' => Yii::t('BirthdayModule.base', 'The group id of the group that should be exluded.'),
            'sidebarSortOrder' => Yii::t('BirthdayModule.base', 'Sort order of the widget in the dashboard sidebar'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'sidebarSortOrder' => Yii::t('BirthdayModule.base', 'Widgets with a lower value are displayed higher.'),
        ];
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $this->module->settings->set('shownDays', $this->shownDays);
        $this->module->settings->set('excludedGroup', $this->excludedGroup);
        $this->module->settings->set('sidebarSortOrder', (int) $this->sidebarSortOrder);

        return true;
    }
}
