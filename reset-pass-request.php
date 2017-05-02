<!DOCTYPE html>
<?php
require_once ("user_functions.php");
require_once ("functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
if($_GET['username'] == ''){
  header('Location: registration.php');
  die();
}
$user_id=GetUserByUsername($dbh, $_GET['username']);

$valNum = SendResetPasswordEmail($dbh, $user_id);
if($valNum == -404){
  echo "We apologize, but we could not find your Username within our system. ";
} else if($valNum == -2){
  echo "It has been less than an hour since your last request,".
       " please be patient as emails may take up to 10 minutes ".
       "to appear in your inbox. Thank you.";
} else if($valNum == -1){
  echo "Too many attempts to access this account have been made within".
       " a short time frame. This account has been restricted.";
} else{
  echo "An email has been sent out. Please follow the link within.";
}

echo "</br> You will be redirected back to the registration page momentarily.";
header('refresh: 10; url=registration.php');
die();

?>
