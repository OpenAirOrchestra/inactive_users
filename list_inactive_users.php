<?php

/*
 * View that lists inactive users in table
 */
class inactiveUserTableView {

	/*
	 * render inactive users listed in query results
	 * user_nicename, user_email, user_login, id, active
	 */
        function render( $results ) {
	
		$siteurl = get_bloginfo('siteurl');

		echo '<table class="widefat" cellpadding="3" cellspacing="3"><tr><th>User</th><th>email</th><th>Last Active</th></tr>';

		if ($results) {
			foreach ($results as $result) {
				
				$user_url = $siteurl . '/wp-admin/users.php?s=' . $result->user_login . '&paged=1';
				$edit_url = $siteurl . '/wp-admin/user-edit.php?user_id=' . $result->id;

				echo '<tr><td class="username column-username"><strong><a href="' . $edit_url .  '">' . $result->user_nicename . '</a></strong>' .

					'<div class="row-actions">' .
					'<span class="Edit"><a href="' . $edit_url . '">Edit</a> | </span>' .
					'<span class="List"><a href="' . $user_url . '">List</a></span>' .
					'</div>' .
					'</td><td class="email column-email"><a href="mailto:' . $result->user_email . '">' . $result->user_email . '</a>' . 
					'</td><td>' . ( $result->active ? $result->active : 'Never' ) . '</td></tr>';
			}
		}

/*
<div class="row-actions"><span class='edit'><a href="user-edit.php?user_id=39&#038;wp_http_referer=%2Fwordpress%2Fwp-admin%2Fusers.php%3Fs%3Dlazerina%26paged%3D1">Edit</a> | </span><span class='delete'><a class='submitdelete' href='users.php?action=delete&amp;user=39&amp;_wpnonce=233fb07e9c'>Delete</a></span></div>
*/
		echo '</table>';
	}
}
?>
