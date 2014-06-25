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
    <div class="panel-heading"><?php echo Yii::t('BirthdayModule.base', 'Upcoming Birthdays'); ?></div>
    <div id="birthdayContent">
<?php 
if(empty($users)) {
	echo '<div class="placeholder">'.Yii::t('BirthdayModule.base', 'No birthday.').'</div>';
}
else {
?>
		<ul id="birthdayList" class="media-list">
<?php
	$currentYear = (new DateTime('now'))->format('Y');
	// run through the array of arrays of users that have birthday in the next days.
	foreach ($users as $days => $birthdayUsers) {
		// run through the array of users that have birthday in $days days. 
		foreach ( $birthdayUsers as $profile ) {
			// check if the profile is valid
			if ($profile != null) {
				// get the corresponding user
				$user = User::model()->findByPk($profile->user_id);
				$birthYear = DateTime::createFromFormat('Y-m-d', $profile->birthday)->format('Y');
				// calculate the age
				$age = $currentYear - $birthYear;				 
?>
			<li class="birthdayEntry">
				<a href="<?php echo $user->getProfileUrl(); ?>">
					<div class="media">
						<!-- Show user image -->
						<img class="media-object img-rounded pull-left" data-src="holder.js/32x32" alt="32x32" 
								style="width: 32px; height: 32px;" src="<?php echo $user->getProfileImage()->getUrl(); ?>">				
			            <!-- Show content -->
			            <div class="media-body">
			                <strong><?php echo $user->displayName; ?></strong>
<?php
				// show when the user has his birthday
				if($days == 0) {
					echo ' -- '.Yii::t('BirthdayModule.base', 'today');
				}
				else if($days == 1) {
					echo ' -- '.Yii::t('BirthdayModule.base', 'tomorrow');
				}
				else {
					echo ' -- '.Yii::t('BirthdayModule.base', 'in').' '.$days.' '.Yii::t('BirthdayModule.base', 'days');
				}		
				// show the users age if allowed		
				if($profile->show_age != 0) {
					echo '<br />'.Yii::t('BirthdayModule.base', 'becomes').' '.$age.' '.Yii::t('BirthdayModule.base', 'years old.');
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
