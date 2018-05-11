<?php

namespace birthday\acceptance;

use birthday\AcceptanceTester;
use tests\codeception\_pages\AccountSettingsPage;
use tests\codeception\_pages\DashboardPage;
use Yii;

class BirthdayCest
{

    public function _before()
    {
        $this->module = Yii::$app->getModule('birthday');
        Yii::$app->cache->flush();
        $this->module->settings->set('shownDays', 2);
    }
    
    public function testBirthdayWidget(AcceptanceTester $I)
    {
        $I->wantToTest('if the birthday widget works as expected');
        $I->amGoingTo('save the termsbox form without activation');
        
        $I->amUser();
        AccountSettingsPage::openBy($I);

        $birthday = (new \DateTime())->sub(new \DateInterval('P29Y'))->format('m/d/y');
        $I->fillField('Profile[birthday]', $birthday);
        $I->click('Save profile');
        $I->wait(2);
        
        $I->amGoingTo('check my birthday widget on the dashboard');
        DashboardPage::openBy($I);
        $I->expectTo('see myself in the birthday widget');
        $I->seeElement('#birthdayContent');
        $I->see('Peter Tester', '#birthdayContent');
    }

}
