<?php

use yii\helpers\Url;
use humhub\libs\Html;
use humhub\libs\TimeZoneHelper;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use humhub\modules\user\models\User;
use humhub\modules\birthday\assets\Assets;

$assets = Assets::register($this);

// Get current date
$currentDate = new DateTime('now');
$currentYear = (int)$currentDate->format('Y');

// Calculate date range for the past week
$endDate = clone $currentDate;
$startDate = clone $currentDate;
$startDate->modify('-7 days');

// Format for comparison
$startDateFormatted = $startDate->format('m-d');
$endDateFormatted = $endDate->format('m-d');

// Find all users with birthdays in the past week
$usersWithBirthdays = [];

// Get all active users
$allUsers = User::find()->where(['status' => User::STATUS_ENABLED])->all();

foreach ($allUsers as $user) {
    // Skip users without birthday data
    if (empty($user->profile->birthday)) {
        continue;
    }

    // Get birthday components
    $birthdayDate = new DateTime($user->profile->birthday);
    $birthYear = (int)$birthdayDate->format('Y');
    $birthMonth = (int)$birthdayDate->format('m');
    $birthDay = (int)$birthdayDate->format('d');
    $birthMonthDay = $birthdayDate->format('m-d');

    // Handle February 29 for non-leap years
    $isLeapDayBirthday = ($birthMonth == 2 && $birthDay == 29);
    $currentYearIsLeap = date('L', strtotime($currentYear . '-01-01')) == 1;

    // For leap day birthdays in non-leap years, use February 28 for comparison
    $comparisonMonthDay = $birthMonthDay;
    if ($isLeapDayBirthday && !$currentYearIsLeap) {
        $comparisonMonthDay = '02-28';
    }

    // Special handling for date ranges that span December-January
    $isInRange = false;
    if ($startDateFormatted <= $endDateFormatted) {
        // Normal case (e.g., Mar 20 - Mar 27)
        $isInRange = ($comparisonMonthDay >= $startDateFormatted && $comparisonMonthDay <= $endDateFormatted);
    } else {
        // Year-spanning case (e.g., Dec 25 - Jan 01)
        $isInRange = ($comparisonMonthDay >= $startDateFormatted || $comparisonMonthDay <= $endDateFormatted);
    }

    if ($isInRange) {
        // Calculate age
        $age = $currentYear - $birthYear;

        // Handle case where birthday hasn't occurred yet this year
        $thisBirthdayDate = DateTime::createFromFormat('Y-m-d', $currentYear . '-' . $birthdayDate->format('m-d'));
        if ($thisBirthdayDate > $currentDate) {
            $age--;
        }

        // Check if birthday is today, handling leap year edge case
        $isBirthdayToday = false;

        if ($isLeapDayBirthday) {
            // For leap day birthdays (Feb 29)
            if ($currentYearIsLeap) {
                // In leap years, check exact match
                $isBirthdayToday = ($currentDate->format('m-d') === '02-29');
            } else {
                // In non-leap years, check against Feb 28
                $isBirthdayToday = ($currentDate->format('m-d') === '02-28');
            }
        } else {
            // Normal case - direct comparison
            $isBirthdayToday = ($birthdayDate->format('m-d') === $currentDate->format('m-d'));
        }

        // Format birthday for this year, handling leap year case
        $birthdayThisYearDate = $birthdayDate->format('m-d');
        $birthdayForThisYearTimestamp = null;

        if ($isLeapDayBirthday && !$currentYearIsLeap) {
            // For Feb 29 birthdays in non-leap years, display as Feb 28
            $birthdayThisYearDate = '02-28';
            $birthdayForThisYearTimestamp = strtotime($currentYear . '-02-28');
        } else {
            $birthdayForThisYearTimestamp = strtotime($currentYear . '-' . $birthdayDate->format('m-d'));
        }

        // Create the birthday entry
        $usersWithBirthdays[] = [
            'user' => $user,
            'birthday' => Yii::$app->formatter->asDate($user->profile->birthday, 'php:M d, Y'),
            'age' => $age,
            'birthdayThisYear' => Yii::$app->formatter->asDate($birthdayForThisYearTimestamp, 'php:M d, Y'),
            'dayOfWeek' => date('l', $birthdayForThisYearTimestamp),
            'sortDate' => $isLeapDayBirthday && !$currentYearIsLeap ? '02-28' : $birthdayDate->format('m-d'),
            'isBirthdayToday' => $isBirthdayToday,
            'isLeapDayBirthday' => $isLeapDayBirthday
        ];
    }
}

// Sort by birthday date (month and day)
usort($usersWithBirthdays, function($a, $b) {
    return strcmp($a['sortDate'], $b['sortDate']);
});

?>

<div class="modal-dialog modal-dialog-normal animated fadeIn">
    <div class="modal-content">
        <?php ModalDialog::begin(['header' => Yii::t('BirthdayModule.base', '<i class="fa fa-birthday-cake"></i> Birthdays This Week'), 'closable' => true]) ?>
        <div class="modal-body">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><?= Yii::t('BirthdayModule.base', 'Members with birthdays from {start} to {end}', [
                        'start' => '<span class="text-info">' . $startDate->format('M d') . '</span>',
                        'end' => '<span class="text-info">' . $endDate->format('M d') . '</span>'
                    ]); ?></strong>
                </div>
                <div class="panel-body">
                    <?php if (empty($usersWithBirthdays)): ?>
                        <div class="empty text-center">
                            <div class="empty-icon">
                                <i class="fa fa-calendar-times-o fa-4x text-muted"></i>
                            </div>
                            <p class="empty-text"><?= Yii::t('BirthdayModule.base', 'No birthdays in the past week.'); ?></p>
                        </div>
                    <?php else: ?>
                        <ul class="media-list">
                            <?php foreach ($usersWithBirthdays as $birthday): ?>
                                <li class="media <?= $birthday['isBirthdayToday'] ? 'birthday-today' : ''; ?>">
                                    <div class="media-left">
                                        <a href="<?= $birthday['user']->getUrl(); ?>" class="profile-link">
                                            <img class="media-object img-rounded"
                                                 alt="<?= Html::encode($birthday['user']->displayName) ?>"
                                                 src="<?= $birthday['user']->getProfileImage()->getUrl(); ?>">
                                                 
                                            <?php if ($birthday['isBirthdayToday']): ?>
                                                <span class="label label-danger birthday-today-badge"><i class="fa fa-birthday-cake"></i></span>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <?= Html::encode($birthday['user']->displayName) ?>
                                            <?php if ($birthday['isBirthdayToday']): ?>
                                                <span class="label label-danger"><i class="fa fa-birthday-cake"></i> <?= Yii::t('BirthdayModule.base', 'Today!'); ?></span>
                                            <?php endif; ?>
                                        </h4>
                                        
                                        <div class="birthday-details">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="detail-row">
                                                        <span class="text-muted"><i class="fa fa-calendar"></i> <?= Yii::t('BirthdayModule.base', 'Birth Date:'); ?></span>
                                                        <strong><?= $birthday['birthday']; ?></strong>
                                                        <span class="label label-default age-label"><?= Yii::t('BirthdayModule.base', '{age} years', ['age' => $birthday['age']]); ?></span>
                                                        <?php if ($birthday['isLeapDayBirthday']): ?>
                                                            <span class="label label-info leap-day-label" title="<?= Yii::t('BirthdayModule.base', 'Born on February 29 (Leap Day)'); ?>">
                                                                <i class="fa fa-calendar-plus-o"></i> <?= Yii::t('BirthdayModule.base', 'Leap Day'); ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="detail-row">
                                                        <span class="text-muted"><i class="fa fa-calendar-check-o"></i> <?= Yii::t('BirthdayModule.base', 'This Year:'); ?></span>
                                                        <strong><?= $birthday['dayOfWeek']; ?>, <?= $birthday['birthdayThisYear']; ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php if (!($birthday === end($usersWithBirthdays))): ?>
                                    <hr class="birthday-separator">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?= ModalButton::cancel(Yii::t('BirthdayModule.base', 'Close'), ['data-modal-close' => true, 'class' => 'btn btn-default']); ?>
        </div>
        <?php ModalDialog::end() ?>
    </div>
</div>