<?php

namespace birthday\functional;

use birthday\FunctionalTester;
use tests\codeception\_pages\DashboardPage;
use humhub\modules\user\models\Profile;
use Yii;

class BirthdayCest
{

    public function _before()
    {
        $this->module = Yii::$app->getModule('birthday');
        Yii::$app->cache->flush();
        $this->module->settings->set('shownDays', 2);
    }

    /**
     * @skip Test is broken by environment issues
     * @param FunctionalTester $I
     */
    public function testBirthdayWidget(FunctionalTester $I)
    {   
        $p1 = Profile::findOne(['user_id' => 1]);
        $p1->birthday = date('m/d').'/'.'87';
        $p1->save();
        
        $p2 = Profile::findOne(['user_id' => 2]);
        $p2->birthday = date('m').'/'.(date('d')+1).'/'.'87';
        $p2->save();
        
        $p3 = Profile::findOne(['user_id' => 3]);
        $p3->birthday = date('m').'/'.(date('d')-1).'/'.'87';
        $p3->save();
        
        $I->wantToTest('if the birthday widget works as expected');
        $I->amGoingTo('save the termsbox form without activation');
        
        $I->amUser();
        $I->amGoingTo('check my birthday widget on the dashboard');
        DashboardPage::openBy($I);
        $I->expectTo('see two user in the birthday widget');
        $I->seeElement('#birthdayContent');
        $I->see('Admin', '#birthdayContent');
        $I->see('Today', '#birthdayContent');
        $I->see('User1', '#birthdayContent');
        $I->see('Tomorrow', '#birthdayContent');
        $I->dontSee('User2', '#birthdayContent');
    }

    /**
     * @skip Test is broken by environment issues
     * @param FunctionalTester $I
     */
    public function testBirthdayWidgetWithLeapYear(FunctionalTester $I)
    {   
        $p1 = Profile::findOne(['user_id' => 1]);
        $p1->birthday = date('m/d').'/'.'88';
        $p1->save();
        
        $p2 = Profile::findOne(['user_id' => 2]);
        $p2->birthday = date('m').'/'.(date('d')+1).'/'.'88';
        $p2->save();
        
        $p3 = Profile::findOne(['user_id' => 3]);
        $p3->birthday = date('m').'/'.(date('d')-1).'/'.'88';
        $p3->save();
        
        $I->wantToTest('if the birthday widget works with leap years');
        $I->amGoingTo('save the termsbox form without activation');
        
        $I->amUser();
        $I->amGoingTo('check my birthday widget on the dashboard');
        DashboardPage::openBy($I);
        $I->expectTo('see two user in the birthday widget');
        $I->seeElement('#birthdayContent');
        $I->see('Admin', '#birthdayContent');
        $I->see('Today', '#birthdayContent');
        $I->see('User1', '#birthdayContent');
        $I->see('Tomorrow', '#birthdayContent');
        $I->dontSee('User2', '#birthdayContent');
    }

}
