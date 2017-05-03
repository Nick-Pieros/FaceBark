<!DOCTYPE html>
<?php
/*
*   reset_pass-request.php -- php that calles the function to
*   send the initial password reset email
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/
require_once ("user_functions.php");
require_once ("functions.php");
require_once ("connect.php");
$dbh=ConnectDB();

// if no username was provided when reaching this page,
// redirect to the registration page
if($_GET['username'] == ''){
  header('Location: registration.php');
  die();
}

$user_id=GetUserByUsername($dbh, $_GET['username']);

$valNum = SendResetPasswordEmail($dbh, $user_id);

// send reset password email can return one of 4 values:
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
  echo "An email has been sent out. Please follow the link inside the email.";
}

echo "</br> You will be redirected back to the registration page momentarily.";
header('refresh: 5; url=registration.php');
die();

?>
