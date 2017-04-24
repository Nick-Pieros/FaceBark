<?php
require_once ("functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
$user_id=$_COOKIE['user_id'];
$userinfo=GetUserInfo($user_id, $dbh);
CreateDogInfo($dbh, '', '', 0, '', $user_id, 0);
header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/profile.php");
 ?>
