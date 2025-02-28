<?php

use yii\helpers\Url;
use humhub\libs\Html;
use humhub\widgets\PanelMenu;
use humhub\modules\birthday\assets\Assets;

$assets = Assets::register($this);
$enabled = (bool) Yii::$app->getModule('birthday')->settings->get('enabled');

?>
<div class="panel panel-default panel-birthday" id="panel-birthday">
    <div class="panel-heading">
        <?= PanelMenu::widget(['id' => 'panel-birthday']); ?>
        <?= Yii::t('BirthdayModule.base', '<strong>Birthdays</strong> within {days} days', ['days' => $dayRange]); ?>
    </div>
    <div class="panel-body">
        <ul id="birthdayList" class="media-list">
            <?php foreach ($users as $user): ?>
                <?php $remainingDays = $this->context->getDays($user); ?>
                
                <li class="birthdayEntry">
                    <?php if ($enabled): ?>
                        <div class="media">
                    <?php else: ?>
                        <!-- Open modal when clicked -->
                        <a href="<?= Url::to(['/birthday/birthday/modal-profile', 'userId' => $user->id]) ?>" data-target="#globalModal">
                            <div class="media">
                    <?php endif; ?>
                                <img class="media-object img-rounded pull-left"
                                     style="width: 32px; height: 32px;"
                                     src="<?= $user->getProfileImage()->getUrl(); ?>">

                                <?php if ($remainingDays == 0): ?>
                                    <img class="media-object img-rounded img-birthday pull-left"
                                         style="width: 16px; height: 16px;"
                                         src="<?= $assets->baseUrl ?>/cake.png">
                                <?php endif; ?>

                                <div class="media-body">
                                    <strong><?= Html::encode($user->displayName); ?></strong>
                                    
                                    <?php
                                    if ($remainingDays == 0) {
                                        echo ' <span class="label label-danger pull-right">' . Yii::t('BirthdayModule.base', 'today') . '</span>';
                                    } elseif ($remainingDays > 0) {
                                        echo ' <span class="label label-default pull-right">' . Yii::t('BirthdayModule.base', 'In {days} days', ['days' => $remainingDays]) . '</span>';
                                    } else {
                                        echo ' <span class="label label-info pull-right">' . Yii::t('BirthdayModule.base', '{days} days ago', ['days' => abs($remainingDays)]) . '</span>';
                                    }

                                    if ($user->profile->birthday_hide_year == '0') {
                                        echo '<br />' . Yii::t('BirthdayModule.base', $remainingDays < 0 ? 'turned {years} years old.' : 'becomes {years} years old.', ['years' => $this->context->getAge($user)]);
                                    }
                                    ?>
                                </div>
                            </div>
                    <?php if (!$enabled): ?>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>