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
        $excludedGroup = (int) Setting::Get('excludedGroup', 'birthday');
        $exclusionSql = "";
        if ($excludedGroup > 0) {
            $exclusionSql = "NOT profile.user_id IN (SELECT group_user.user_id FROM group_user WHERE group_user.group_id= " . $excludedGroup . ") AND ";
        }

        $nextBirthDaySql = "DATE_ADD(profile.birthday, INTERVAL YEAR(CURDATE())-YEAR(profile.birthday) + IF((CURDATE() > DATE_ADD(`profile`.birthday, INTERVAL (YEAR(CURDATE())-YEAR(profile.birthday)) YEAR)),1,0) YEAR)";
        $birthdayCondition = $exclusionSql . $nextBirthDaySql . " BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL " . $range . " DAY)";

        $users = User::find()
                ->addSelect(['*', 'user.*', 'profile.*', new \yii\db\Expression($nextBirthDaySql . ' as next_birthday')])
                ->joinWith('profile')
                ->where($birthdayCondition)
                ->addOrderBy(['next_birthday' => SORT_ASC])
                ->active()
                ->limit(10)
                ->all();

        if (count($users) == 0) {
            return;
        }

        return $this->render('birthdayPanel', [
            'users' => $users,
            'dayRange' => $range,
        ]);
    }

    public function getDays($user)
    {
        $now = new \DateTime('now');
        $now->setTime(00, 00, 00);
        $nextBirthday = new \DateTime(date('y') . '-' . Yii::$app->formatter->asDate($user->profile->birthday, 'php:m-d'));
        $interval = $now->diff($nextBirthday);

        $days = (int) $interval->format('%R%a');

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
