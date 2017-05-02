<?php
require_once ("user_functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
$tmp_user_id=$_COOKIE['tmp_user_id'];

$valNum = SendValidationEmail($dbh, $tmp_user_id);
if($valNum == -404){
  echo "We apologize, but we could not find your Username within our system. ";
} else if($valNum == -2){
  echo "It has been less than an hour since your last request, ".
       "please be patient as emails may take up to 10 minutes ".
       "to appear in your inbox. If this is your first attempt to ".
       "log in, please verify your email address through the ".
       "link that was emailed to you. Thank you.";
       
} else if($valNum == -1){
  echo "Too many attempts to access this account have been made within ".
       "a short time frame. This account has been restricted.";
} else{
  echo "An email has been sent out. Please follow the link inside the email.";
}
setcookie ("tmp_user_id", "", 1);
unset($_COOKIE['tmp_user_id']);

echo "</br> You will be redirected back to the registration page momentarily.";
header('refresh: 5; url=registration.php');
die();
 ?>
