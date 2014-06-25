<?php

/**
 * BirthdaySidebarWidget displays the users of upcoming birthdays.
 *
 * It is attached to the dashboard sidebar.
 *
 * @package humhub.modules.birthday.widgets
 * @author Sebastian Stumpf
 */
class BirthdaySidebarWidget extends HWidget {
	public function run() {
		$this->render ( 'birthdayPanel', array (
				'users' => $this->getBirthdayUsers ( 4 ) 
		) );
	}
	
	/**
	 * Get the User objects from the db that are born at the given date and have allowed to show their birthday.
	 *
	 * @param array $range
	 *        	the range that should be checked from today on in days.
	 * @return array of arrays of users, indicated by ranges.
	 */
	private function getBirthdayUsers($range = 0) {
		$users = array ();
		for($i = 0; $i <= $range; $i ++) {
			// select the users that have birthday in $i days
			$temp = Profile::model ()->findAll ( 'MONTH(DATE(`birthday`)) = MONTH(DATE_ADD(DATE(NOW()),INTERVAL ' . $i . ' DAY)) AND DAY(DATE(`birthday`)) = DAY(DATE_ADD(DATE(NOW()),INTERVAL ' . $i . ' DAY)) AND `show_birthday` = 1' );
			// and add them to the array if there are any
			if(!empty($temp)) {
				$users [$i] = $temp;
			} 
		}
		return $users;
	}
}

?>
