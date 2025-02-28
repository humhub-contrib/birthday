<?php

namespace humhub\modules\birthday\widgets;

use Yii;
use humhub\models\Setting;
use humhub\modules\user\models\User;

/**
 * BirthdaySidebarWidget displays users with past and upcoming birthdays.
 *
 * It is attached to the dashboard sidebar.
 *
 * @package humhub.modules.birthday.widgets
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

        $nextBirthDaySql = "DATE_ADD(profile.birthday, INTERVAL YEAR(CURDATE())-YEAR(profile.birthday) + 
            IF((CURDATE() > DATE_ADD(`profile`.birthday, INTERVAL (YEAR(CURDATE())-YEAR(profile.birthday)) YEAR)),1,0) YEAR)";

        $birthdayCondition = $exclusionSql . "(
            " . $nextBirthDaySql . " BETWEEN DATE_SUB(CURDATE(), INTERVAL " . $range . " DAY) AND CURDATE()
            OR " . $nextBirthDaySql . " BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL " . $range . " DAY)
        )";

        $users = User::find()
            ->addSelect(['*', 'user.*', 'profile.*', new \yii\db\Expression($nextBirthDaySql . ' as next_birthday')])
            ->joinWith('profile')
            ->where($birthdayCondition)
            ->addOrderBy(['next_birthday' => SORT_ASC])
            ->active()
            ->limit(10)
            ->all();

        $pastBirthdays = [];
        foreach ($users as $user) {
            if ($this->getDays($user) < 0) {
                $pastBirthdays[] = [
                    'name' => Html::encode($user->displayName),
                    'date' => Yii::$app->formatter->asDate($user->profile->birthday, 'php:F j'),
                    'age' => $this->getAge($user)
                ];
            }
        }

        if (count($users) == 0) {
            return;
        }

        return $this->render('birthdayPanel', [
            'users' => $users,
            'dayRange' => $range,
            'pastBirthdays' => $pastBirthdays
        ]);
    }

    public function getDays($user)
    {
        $now = new \DateTime('now');
        $now->setTime(00, 00, 00);

        $nextBirthday = new \DateTime(date('Y') . '-' . Yii::$app->formatter->asDate($user->profile->birthday, 'php:m-d'));
        $interval = $now->diff($nextBirthday);
        $days = (int) $interval->format('%R%a');

        // Handle turn of the year and past birthdays
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