<?php
require_once ("user_functions.php");
require_once ( "functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
$user_id = ValidateUser($dbh, $_GET['key']);
if($user_id != 0){
  echo "Thank you for confirming your account! <br/>".
       "You will be redirected to your profile page momentarily.";
  setcookie("user_id", $user_id);
  CreateDogInfo($dbh, '', '', 0, '', $user_id, 0);
  header('refresh: 5; url=profile.php');
  die();
} else {
  echo "We were unable to find an account matching this confirmation key. <br/>".
       "You will be redirected back to the registration page shortly.";
  header('refresh: 5; url=registration.php');
  die();
}
 ?>
