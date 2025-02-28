<?php

namespace humhub\modules\birthday\controllers;

use Yii;
use humhub\components\Controller;
use humhub\modules\birthday\Module;
use humhub\modules\user\models\User;

class BirthdayController extends Controller
{
    /**
     * Action to render the birthday sidebar widget
     */
    public function actionIndex()
    {
        // Get the range of days to show upcoming birthdays
        $range = (int) \humhub\models\Setting::Get('shownDays', 'birthday');
        
        // Fetch users having birthdays in the next $range days
        $birthdayUsers = User::find()
            ->joinWith('profile')
            ->where(['>', 'profile.birthday', new \yii\db\Expression('CURDATE()')])
            ->andWhere(['<=', 'profile.birthday', new \yii\db\Expression('DATE_ADD(CURDATE(), INTERVAL :range DAY)', [':range' => $range])])
            ->limit(10)
            ->all();
        
        return $this->render('index', [
            'users' => $birthdayUsers,
            'dayRange' => $range,
        ]);
    }

    /**
     * Action to render a modal displaying a user's full profile details.
     * 
     * @param int $userId The user ID to show the profile for
     * @return string Rendered modal view
     */
    public function actionModalProfile($userId)
    {
        // Fetch the user based on the user ID passed in the URL
        $user = User::findOne(['id' => $userId]);

        // Check if the user exists
        if (!$user) {
            // If the user does not exist, show an error message
            return Yii::t('BirthdayModule.base', 'User not found.');
        }

        // Render the profile modal with the user data
        return $this->renderAjax('profileModal', [
            'user' => $user
        ]);
    }

    public function getAge($user)
    {
        $birthday = new \DateTime($user->profile->birthday);
        $age = $birthday->diff(new \DateTime('now'))->y;

        return $age;
    }
}