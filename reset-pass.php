<!DOCTYPE html>
<form name='myForm' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='post' >
   <input id='password' type='password' name='password' placeholder='Password'> <span class='error'><?php echo $passwordErr?></span><br/>
   <input id='confirm-password' type='password' name='confpassword' placeholder='Confirm Password'> <span class='error'><?php echo $confPasswordErr?></span><br/>
   <input type='submit' value='Reset Password!' class='green-btn'>
</form>


<?php
require_once ("user_functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
if($_SERVER['REQUEST_METHOD'] == 'GET'){
  $key = $_GET['key'];
  setcookie("key", $key);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (empty($_POST["password"])) {
      echo "*Password is required";
  } else {
    //http://stackoverflow.com/questions/11873990/create-preg-match-for-password-validation-allowing
    if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $_POST['password'])) {
      echo "*Required: 1 number, 1 letter, between 8-12 characters";
    } else {
      if (empty($_POST["confpassword"])) {
          echo "*Confirm password";
      } else {
        if ($_POST["password"] == $_POST["confpassword"]){ // if passwords match, reset password
          $password = $_POST["password"];
          $success = ResetPassword($dbh, $password, $_COOKIE['key']);  
          if($success != 0){
            setcookie ("key", "", 1);
            unset($_COOKIE['key']);
            echo "Password reset successfully! <br/>".
                 "You will be redirected back to the registration page shortly.";
            header('refresh: 5; url=registration.php');
            die();
          } else if ($success == 0){
            setcookie ("key", "", 1);
            unset($_COOKIE['key']);
            echo "We were unable to find an account matching this confirmation key. <br/>".
                 "You will be redirected back to the registration page shortly.";
            header('refresh: 5; url=registration.php');
            die();
          }
        } else{
          echo "*Passwords do not match!";
        }
      }
    }
  }

}


 ?>
