<?php

namespace birthday\functional;

use birthday\FunctionalTester;
use humhub\modules\birthday\Module;
use humhub\modules\user\models\Profile;
use tests\codeception\_pages\DashboardPage;
use Yii;

/**
 * @group skipTravis
 * @package birthday\functional
 */
class BirthdayCest
{
    /**
     * @var Module
     */
    public $module;

    public function _before()
    {
        $this->module = Yii::$app->getModule('birthday');
        Yii::$app->cache->flush();
        $this->module->settings->set('shownDays', 2);
    }

    /**
     * @param FunctionalTester $I
     */
    public function testBirthdayWidget(FunctionalTester $I)
    {
        $p1 = Profile::findOne(['user_id' => 1]);
        $p1->birthday = '1987-' . date('m-d');
        $p1->save();

        $p2 = Profile::findOne(['user_id' => 2]);
        $p2->birthday = '1987-' . date('m-d', time() + 86400);
        $p2->save();

        $p3 = Profile::findOne(['user_id' => 3]);
        $p3->birthday = '1987-' . date('m-d', time() - 86400);
        $p3->save();

        $I->wantToTest('if the birthday widget works as expected');
        $I->amGoingTo('save the termsbox form without activation');

        $I->amUser3();
        $I->amGoingTo('check my birthday widget on the dashboard');
        $I->amOnDashboard();
        $I->expectTo('see two user in the birthday widget');
        $I->seeElement('#birthdayContent');
        $I->see('Admin Tester', '#birthdayContent');
        $I->see('Today', '#birthdayContent');
        $I->see('Peter Tester', '#birthdayContent');
        $I->see('Tomorrow', '#birthdayContent');
        $I->dontSee('Sara', '#birthdayContent');
    }

    /**
     * @param FunctionalTester $I
     */
    public function testBirthdayWidgetWithLeapYear(FunctionalTester $I)
    {
        $p1 = Profile::findOne(['user_id' => 1]);
        $p1->birthday = '1988-' . date('m-d');
        $p1->save();

        $p2 = Profile::findOne(['user_id' => 2]);
        $p2->birthday = '1988-' . date('m-d', time() + 86400);
        $p2->save();

        $p3 = Profile::findOne(['user_id' => 3]);
        $p3->birthday = '1988-' . date('m-d', time() - 86400);
        $p3->save();

        $I->wantToTest('if the birthday widget works with leap years');
        $I->amGoingTo('save the termsbox form without activation');

        $I->amUser3();
        $I->amGoingTo('check my birthday widget on the dashboard');
        DashboardPage::openBy($I);
        $I->expectTo('see two user in the birthday widget');
        $I->seeElement('#birthdayContent');
        $I->see('Admin Tester', '#birthdayContent');
        $I->see('Today', '#birthdayContent');
        $I->see('Peter Tester', '#birthdayContent');
        $I->see('Tomorrow', '#birthdayContent');
        $I->dontSee('Sara', '#birthdayContent');
    }

}
