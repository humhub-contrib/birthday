<?php

use humhub\helpers\Html;
use humhub\modules\birthday\assets\Assets;
use humhub\widgets\PanelMenu;
use humhub\widgets\bootstrap\Badge;

/**
 * View File for the BirthdaySidebarWidget
 *
 * @uses User $users the profile data of all the users that have birthday the next days.
 *
 * @package humhub.modules.birthday.widgets.views
 * @author Sebastian Stumpf
 */
$assets = Assets::register($this);
?>
<div class="panel panel-default panel-birthday" id="panel-birthday">
    <?= PanelMenu::widget(['id' => 'panel-birthday']); ?>
    <div class="panel-heading">
        <?= Yii::t('BirthdayModule.base', '<strong>Birthdays</strong> within the next {days} days', ['days' => $dayRange]); ?>
    </div>
    <div class="collapse show" id="humhubmodulesbirthdaywidgetsbirthdaysidebarwidget">
        <ul id="birthdayList" class="hh-list">
            <?php foreach ($users as $user): ?>
                <?php
                $remainingDays = $this->context->getDays($user);
                ?>
                <a class="birthdayEntry w-100 d-flex" href="<?= $user->getUrl() ?>">
                    <div class="d-flex w-100">
                        <div class="flex-shrink-0 me-3 pt-1 img-profile-space">
                        <!-- Show user image -->
                        <img class="rounded" data-src="holder.js/32x32"
                                alt="32x32"
                                style="width: 32px; height: 32px;"
                                src="<?= $user->getProfileImage()->getUrl(); ?>">
                        <?php if ($remainingDays == 0) : ?>
                            <img class="rounded img-birthday"
                                    data-src="holder.js/16x16" alt="16x16"
                                    style="width: 16px; height: 16px;"
                                    src="<?= $assets->baseUrl ?>/cake.png">
                        <?php endif; ?>
                        </div>
                        <!-- Show content -->
                        <div class="flex-grow-1 text-break">
                            <strong><?= Html::encode($user->displayName); ?></strong>
                            <?php
                            // show when the user has his birthday
                            if ($remainingDays == 0) {
                                echo Badge::danger(Yii::t('BirthdayModule.base', 'today'))
                                    ->cssClass('float-end');
                            } else if ($remainingDays == 1) {
                                echo Badge::light(Yii::t('BirthdayModule.base', 'Tomorrow'))
                                    ->cssClass('float-end');
                            } else {
                                echo Badge::light(Yii::t('BirthdayModule.base', 'In {days} days', ['days' => $remainingDays]))
                                    ->cssClass('float-end');
                            }
                            // show the users age if allowed
                            if ($user->profile->birthday_hide_year == '0') {
                                echo '<br />' . Yii::t('BirthdayModule.base', 'becomes {years} years old.', ['years' => $this->context->getAge($user)]);
                            }
                            ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<style type="text/css">
    .img-birthday {
        position: absolute;
        top: 26px;
        left: 24px;
    }
</style>
