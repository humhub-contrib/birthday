<?php

class BirthdayConfigureForm extends CFormModel {

    public $shownDays;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('shownDays', 'required'),
        	array('shownDays', 'compare', 'compareValue'=>'0', 'operator'=>'>=', 'message'=>'The number of days future birthdays are shown must not be negative.'),
        	array('shownDays', 'compare', 'compareValue'=>'7', 'operator'=>'<=', 'message'=>'The number of days future birthdays are shown must not be greater than a week.'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'shownDays' => Yii::t('BirthdayModule.base', 'The number of days future bithdays will be shown within.'),
        );
    }

}