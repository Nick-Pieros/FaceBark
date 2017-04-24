<?php
// prepare the profile information
require_once ("functions.php");
require_once ("connect.php");
$dbh = ConnectDB();
$user_id = $_COOKIE['user_id'];
$doggoInfo = GetDogInfo($user_id, $dbh)[0];
//weight isn't working for some reason
UpdateDogInfo($dbh, $_POST['name'], $_POST['breed'], $_POST['weight'], trim($_POST['bio']), $user_id, GetUploadID($dbh, $doggoInfo->file_name));
header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/profile.php");
?>
