<?php
/*
Plugin Name: Inactive Users
Plugin URI: https://github.com/OpenAirOrchestra/inactive_users
Description: Lists user accounts that may be inactive
Version: 1.2
Author: DarrylF
Author URI: http://www.thecarnivalband.com
License: GPL2
GitHub Plugin URI: https://github.com/OpenAirOrchestra/inactive_users
*/
?>
<?php
/*  Copyright 2011  The Carnival Band (email : oaowebmonkey@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$include_folder = dirname(__FILE__);
require_once $include_folder . '/list_inactive_users.php';
require_once $include_folder . '/search_form.php';


/*
 * Main class for listing expired users.  Handles activation, hooks, etc.
 */
class expiredUsers {

	private $inactiveUserTableView, $inactiveUsersSearchFormView;

        function list_inactive_users() {
 		echo '<div class="wrap">';
                echo "<h2>Inactive Users</h2>";

		$min_user_age =  360; // Days
		$min_user_activity = 180; // Days

		if (isset($_GET['age']) && $_GET['age'] && intval($_GET['age'])) {
			$min_user_age = intval($_GET['age']);
		}
		if (isset($_GET['inactive']) && $_GET['inactive'] && intval($_GET['inactive'])) {
			$min_user_activity = intval($_GET['inactive']);
		}

		if (! $this->inactiveUsersSearchFormView) {
			$this->inactiveUsersSearchFormView = new inactiveUsersSearchFormView;
		}
		$this->inactiveUsersSearchFormView->render($min_user_age, $min_user_activity);
	
		global $wpdb;
		$loginlog_table_name = $wpdb->prefix . "loginlog";
		$user_table_name = $wpdb->prefix . "users";

		$query = "SELECT " . $user_table_name . ".id, user_email, user_nicename, user_login, active FROM " .
			$user_table_name . " LEFT JOIN " . $loginlog_table_name .
			" ON (" . 
			$user_table_name . ".user_login = " . 	
			$loginlog_table_name . ".username) " .
			"WHERE " .
			"DATEDIFF(CURRENT_DATE, user_registered) > %d " .
			" AND (success is null OR (success = 1 AND " .
			"DATEDIFF(CURRENT_DATE, " . $loginlog_table_name . ".active) > %d ))" .
			"ORDER BY " . $loginlog_table_name . ".active;" ;

		$query = $wpdb->prepare ( $query, $min_user_age, $min_user_activity );

		// Debugging
		// echo $query;

		$results = $wpdb->get_results($query);

		if (! $this->inactiveUserTableView) {
			$this->inactiveUserTableView = new inactiveUserTableView;
		}
		$this->inactiveUserTableView->render($results);

 		echo '</div>';
	}
 
	/*
         * Create admin menu(s) for this plugin.  
         */
        function create_admin_menu() {

                // Add tools page
                add_users_page('Inactive Users', 'Inactive Users', 8, 'list-inactive-users', array($this, 'list_inactive_users'));

		// Add submenu to users page
		// add_submenu_page('users.php','Inactive Users','Inactive Users',8, array($this, 'list_inactive_users'));

        }

};

// instantiate class
$EXPIREDUSERS = new expiredUsers;

add_action('admin_menu', array($EXPIREDUSERS, 'create_admin_menu'));


?>
