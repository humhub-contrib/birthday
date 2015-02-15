<?php
/**
 * View File for the BirthdaySidebarWidget
 *
 * @uses User $users the profile data of all the users that have birthday the next days.
 *
 * @package humhub.modules.birthday.widgets.views
 * @author Sebastian Stumpf
 */
?>

<div class="panel panel-default panel-birthday">
    <div class="panel-heading">
        <strong><?php echo Yii::t('BirthdayModule.base', 'Upcoming'); ?></strong> <?php echo Yii::t('BirthdayModule.base', 'birthdays'); ?>
    </div>
    <div id="birthdayContent">
        <?php
        if (empty($users)) {
            echo '<div class="placeholder">' . Yii::t('BirthdayModule.base', 'No birthday.') . '</div>';
        } else {
            ?>
            <ul id="birthdayList" class="media-list">
                <?php
                //$currentYear = (new DateTime('now'))->format('Y');
                $currentYear = date("Y");
                // run through the array of arrays of users that have birthday in the next days.
                foreach ($users as $days => $birthdayUsers) {
                    // run through the array of users that have birthday in $days days.
                    foreach ($birthdayUsers as $profile) {
                        // check if the profile is valid
                        if ($profile != null) {
                            // get the corresponding user
                            $user = User::model()->findByPk($profile->user_id);
                            $birthYear = DateTime::createFromFormat('Y-m-d H:i:s', $profile->birthday)->format('Y');
                            // calculate the age
                            $age = $currentYear - $birthYear;
                            ?>
                            <li class="birthdayEntry">
                                <a href="<?php echo $user->getProfileUrl(); ?>">
                                    <div class="media">
                                        <!-- Show user image -->
                                        <img class="media-object img-rounded pull-left" data-src="holder.js/32x32"
                                             alt="32x32"
                                             style="width: 32px; height: 32px;"
                                             src="<?php echo $user->getProfileImage()->getUrl(); ?>">
                                        <?php if ($days == 0) : ?>
                                            <img class="media-object img-rounded img-birthday pull-left"
                                                 data-src="holder.js/16x16" alt="16x16"
                                                 style="width: 16px; height: 16px;"
                                                 src="<?php echo Yii::app()->getModule('birthday')->assetsUrl; ?>/cake.png">
                                        <?php endif; ?>

                                        <!-- Show content -->
                                        <div class="media-body">
                                            <strong><?php echo CHtml::encode($user->displayName); ?></strong>
                                            <?php
                                            // show when the user has his birthday
                                            if ($days == 0) {
                                                echo ' <span class="label label-danger">' . Yii::t('BirthdayModule.base', 'today') . '</span>';
                                            } else if ($days == 1) {
                                                echo ' (' . Yii::t('BirthdayModule.base', 'Tomorrow') . ')';
                                            } else {
                                                echo ' (' . Yii::t('BirthdayModule.base', 'in') . ' ' . $days . ' ' . Yii::t('BirthdayModule.base', 'days') . ')';
                                            }
                                            // show the users age if allowed
                                            if ($profile->birthday_hide_year == '0') {
                                                echo '<br />' . Yii::t('BirthdayModule.base', 'becomes') . ' ' . $age . ' ' . Yii::t('BirthdayModule.base', 'years old.');
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        <?php
                        }
                    }
                }
                ?>
            </ul>
        <?php
        }
        ?>
    </div>
</div>

<style type="text/css">
    .img-birthday {
        position: absolute;
        top: 32px;
        left: 30px;
    }
</style>
