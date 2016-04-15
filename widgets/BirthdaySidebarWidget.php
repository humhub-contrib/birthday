<?php

namespace humhub\modules\birthday\widgets;

use Yii;
use humhub\modules\user\models\User;
use humhub\models\Setting;

/**
 * BirthdaySidebarWidget displays the users of upcoming birthdays.
 *
 * It is attached to the dashboard sidebar.
 *
 * @package humhub.modules.birthday.widgets
 * @author Sebastian Stumpf
 */
class BirthdaySidebarWidget extends \yii\base\Widget
{

    public function run()
    {
        $range = (int) Setting::Get('shownDays', 'birthday');

        $birthdayCondition = "DATE_ADD(profile.birthday, 
                INTERVAL YEAR(CURDATE())-YEAR(profile.birthday)
                         + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(profile.birthday),1,0)
                YEAR)
            BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL " . $range . " DAY);";

        $users = User::find()
                ->joinWith('profile')
                ->where($birthdayCondition)
                ->limit(10)
                ->all();

        // Sort birthday list
        usort($users, function($a, $b) {
            return $this->getDays($a) - $this->getDays($b);
        });

        if (count($users) == 0) {
            return;
        }

        return $this->render('birthdayPanel', array(
                    'users' => $users,
                    'dayRange' => $range
        ));
    }

    public function getDays($user)
    {
        $now = new \DateTime('now');
        $now->setTime(00, 00, 00);
        $nextBirthday = new \DateTime(date('y') . '-' . Yii::$app->formatter->asDate($user->profile->birthday, 'php:m-d'));

        $days = $nextBirthday->diff($now)->days;

        // Handle turn of year
        if ($days < 0) {
            $nextBirthday->modify('+1 year');
            $days = $nextBirthday->diff($now)->days;
        }

        return $days;
    }

    public function getAge($user)
    {
        $birthday = new \DateTime($user->profile->birthday);
        $age = $birthday->diff(new \DateTime('now'))->y;

        if ($this->getDays($user) != 0) {
            $age++;
        }

        return $age;
    }

}

?>
