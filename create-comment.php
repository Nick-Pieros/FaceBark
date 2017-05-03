
<?php
/*
*   create-comment.php -- creates a comment on a post
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/

// perform necessary requires and create database handler
require_once ("functions.php");
require_once ("connect.php");
require_once ("user_functions.php");
$dbh = ConnectDB();

$post_id = $_POST['post_id'];

// sanitize input of html and php tags and explode for parsing
$new_comment = strip_tags($_POST['new_comment']);
$new_comment = explode(" ", $new_comment);
$mailingList = [];

// for each element in the comment array, if the element starts with @ or #
// surround it the the relevant anchor and store into the database with the
// anchor tag
foreach($new_comment as & $value) {
	if (strpos($value, '@') === 0) {
		array_push($mailingList, substr($value, 1));
		$value = str_replace($value, ' <a href="user.php?username=' . preg_replace("/[^a-zA-Z0-9]/", "", substr($value, 1)) . '">' . $value . '</a> ', $value); // preg_replace needed so as to not include special chars
	}

	if (strpos($value, '#') === 0) {
		$value = str_replace($value, ' <a href="search.php?searchTerm=' . preg_replace("/[^a-zA-Z0-9]/", "", substr($value, 1)) . '&method=Tags">' . $value . '</a> ', $value);
		CreateHashtag($dbh, substr($value, 1) , $post_id, 0);
	}
}

// perform the database actions
echo CreateComment($dbh, $post_id, $_COOKIE['user_id'], implode(" ", $new_comment));

// send an email to all users that have been tagged if they exist in the database
if ($post_id != 0) {
	SendNotification($dbh, $mailingList, "comment", "http://elvis.rowan.edu/~blackc6/awp/FaceBark/post.php?post_id=" . $post_id);
}
?>
