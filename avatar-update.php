<?php
/*
*   avatar-update.php -- calls necessary functions to retrieve, upload, and update
*		the	dog's avatar picture
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/

// include the necessary php file that performs the uploading of the file
// to the server
include 'upload2.php';

// user_id is set to the user logged in.
// if the logged in user is an admin, set the user_id to
// that of the profile the admin is updating
if (IsAdminUser($dbh, $user_id)) {
	$user_id = $_POST['profile_id'];
}

// retrieve current dog information and upload id of current file to be uploaded
// call update dog info function with information attained
$doggoInfo = GetDogInfo($user_id, $dbh) [0];
$upload_id = GetUploadId($dbh, $filename);
UpdateDogInfo($dbh, $doggoInfo->dog_name, $doggoInfo->dog_breed, $doggoInfo->dog_weight, $doggoInfo->dog_bio, $user_id, $upload_id);

// if an admin is updating another user's profile,
// the logged in id will not match the $user_id
if ($_COOKIE['user_id'] != $user_id) {
  // return to the user's profile page
	header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/user.php?username=" . $_POST['username']);
}
else {
  // return to the logged in user's profile page
	header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/profile.php");
}
die();
?>
