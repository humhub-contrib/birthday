<?php

namespace humhub\modules\birthday\models;

use Yii;

/**
 * BirthdayConfigureForm defines the configurable fields.
 *
 * @package humhub.modules.birthday.forms
 * @author Sebastian Stumpf
 */
class BirthdayConfigureForm extends \yii\base\Model
{

    public $shownDays;
    public $excludedGroup;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('shownDays', 'required'),
            array('shownDays', 'integer', 'min' => 0, 'max' => 90),
            array('excludedGroup', 'integer', 'min' => 1, 'max' => 1000000),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'shownDays' => Yii::t('BirthdayModule.base', 'The number of days future birthdays will be shown within.'),
            'excludedGroup' => Yii::t('BirthdayModule.base', 'The group id of the group that should be exluded.'),
        );
    }

}
