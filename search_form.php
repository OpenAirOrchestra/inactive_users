<?php

/*
 * View that renders a search form
 */
class inactiveUsersSearchFormView {

	/*
	 * render inactive users search form
	 */
        function render($min_user_age, $min_user_activity) {
?>
<form action="" method="get">

<p class="search-box">
	Accounts Older Than (Days):
	<input type="text" id="user-max-age" name="age" value="<?php echo $min_user_age; ?>" />
	Inactive (Days):
	<input type="text" id="user-inactive" name="inactive" value="<?php echo $min_user_activity; ?>" />
	<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
	<input type="submit" name="" id="search-submit" class="button" value="Search Users"/></p>
<?php

	}
}
?>
