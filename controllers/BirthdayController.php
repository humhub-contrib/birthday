<?php

namespace humhub\modules\birthday\controllers;

use Yii;
use humhub\components\Controller;
use humhub\modules\user\models\User;
use humhub\modules\birthday\models\BirthdayConfigureForm;

class BirthdayController extends Controller
{
    /**
     * Action to render the birthday sidebar widget
     */
    public function actionIndex()
    {
        $config = new BirthdayConfigureForm();
        $config->loadSettings();

        $range = (int) $config->shownDays;

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
        $user = User::findOne(['id' => $userId]);

        if (!$user) {
            return Yii::t('BirthdayModule.base', 'User not found.');
        }

        return $this->renderAjax('profileModal', [
            'user' => $user
        ]);
    }

    /**
     * Get the age of a user based on their profile birthday.
     *
     * @param User $user
     * @return int|null Age or null if no birthday set
     */
    public function getAge(User $user): ?int
    {
        if (empty($user->profile->birthday)) {
            return null;
        }

        $birthday = new \DateTime($user->profile->birthday);
        return $birthday->diff(new \DateTime('now'))->y;
    }
}
