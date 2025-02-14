<?php

use humhub\libs\Html;
use humhub\widgets\PanelMenu;
use humhub\modules\birthday\Assets;

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
    
    <div class="panel-heading">
        <?= PanelMenu::widget(['id' => 'panel-birthday']); ?>
        <?= Yii::t('BirthdayModule.base', '<strong>Birthdays</strong> within the next {days} days', ['days' => $dayRange]); ?>
    </div>
    <div class="panel-body">
        <div id="birthdayContent">
            <ul id="birthdayList" class="media-list">
                <?php foreach ($users as $user): ?>
                    <?php
                    $remainingDays = $this->context->getDays($user);
                    ?>
                    <li class="birthdayEntry">
                        <a href="<?= $user->getUrl(); ?>">
                            <div class="media">
                                <!-- Show user image -->
                                <img class="media-object img-rounded pull-left" data-src="holder.js/32x32"
                                     alt="32x32"
                                     style="width: 32px; height: 32px;"
                                     src="<?= $user->getProfileImage()->getUrl(); ?>">
                                     <?php if ($remainingDays == 0) : ?>
                                    <img class="media-object img-rounded img-birthday pull-left"
                                         data-src="holder.js/16x16" alt="16x16"
                                         style="width: 16px; height: 16px;"
                                         src="<?= $assets->baseUrl ?>/cake.png">
                                     <?php endif; ?>
                                <!-- Show content -->
                                <div class="media-body">
                                    <strong><?= Html::encode($user->displayName); ?></strong>
                                    <?php
                                    // show when the user has his birthday
                                    if ($remainingDays == 0) {
                                        echo ' <span class="label label-danger pull-right">' . Yii::t('BirthdayModule.base', 'today') . '</span>';
                                    } else if ($remainingDays == 1) {
                                        echo ' <span class="label label-default pull-right">' . Yii::t('BirthdayModule.base', 'Tomorrow') . '</span>';
                                    } else {
                                        echo ' <span class="label label-default pull-right">' . Yii::t('BirthdayModule.base', 'In {days} days', ['days' => $remainingDays]) . '</span>';
                                    }
                                    // show the users age if allowed
                                    if ($user->profile->birthday_hide_year == '0') {
                                        echo '<br />' . Yii::t('BirthdayModule.base', 'becomes {years} years old.', ['years' => $this->context->getAge($user)]);
                                    }
                                    ?>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<style type="text/css">
    .img-birthday {
        position: absolute;
        top: 32px;
        left: 30px;
    }
</style>
