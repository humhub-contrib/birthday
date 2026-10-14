<?php

namespace humhub\modules\birthday\widgets;

use humhub\modules\birthday\models\BirthdayConfigureForm;
use humhub\modules\user\models\ProfileField;
use humhub\modules\user\models\User;
use yii\base\Widget;

/**
 * BirthdaySidebarWidget displays the users of upcoming birthdays.
 *
 * It is attached to the dashboard sidebar.
 *
 * @package humhub.modules.birthday.widgets
 * @author Sebastian Stumpf
 */
class BirthdaySidebarWidget extends Widget
{
    public function run()
    {
        // Respect the visibility setting of the profile field
        $field = ProfileField::findOne(['internal_name' => 'birthday']);
        if ($field === null || !$field->visible) {
            return '';
        }

        $config = new BirthdayConfigureForm();
        $range = (int) $config->shownDays;
        $excludedGroup = (int) $config->excludedGroup;
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
                ->available()
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

    /**
     * Next birthday date (today or later), consistent with the SQL in run():
     * a birthday on Feb 29 is moved to Feb 28 in non-leap years.
     */
    protected function getNextBirthday($user): \DateTime
    {
        $birthday = new \DateTime($user->profile->birthday);
        $today = new \DateTime('today');

        $year = (int) $today->format('Y');
        $next = $this->getBirthdayInYear($birthday, $year);
        if ($next < $today) {
            $next = $this->getBirthdayInYear($birthday, $year + 1);
        }

        return $next;
    }

    private function getBirthdayInYear(\DateTime $birthday, int $year): \DateTime
    {
        $month = (int) $birthday->format('n');
        $day = (int) $birthday->format('j');
        if ($month === 2 && $day === 29 && !checkdate(2, 29, $year)) {
            $day = 28;
        }

        return (new \DateTime())->setDate($year, $month, $day)->setTime(0, 0, 0);
    }

    public function getDays($user)
    {
        return (int) (new \DateTime('today'))->diff($this->getNextBirthday($user))->days;
    }

    public function getAge($user)
    {
        $birthday = new \DateTime($user->profile->birthday);

        return (int) $this->getNextBirthday($user)->format('Y') - (int) $birthday->format('Y');
    }

}
