<!DOCTYPE html>
<?php
/*
*   reset_pass.php -- php that displays the password and confirmation password
*   after clicking the link to reset your password
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/
?>
<head>
  <meta charset='UTF-8'/>
  <title>Facebark - Login</title>
  <link href='css/basic-format.css' rel='stylesheet'/>
  <link href='images/doggo1.jpg' rel='icon'/>
  <script>

  </script>
</head>
<body>
  <div class='menu-container'>
       <div class='menu'>
         <div class='logo'><a href='gallery.php?page=1'><img src='images/fbark-logo.png' /></a></div>
       </div>
    </div>
  <div class='content'>
    <h3>Please enter your new password!</h3>
  <form name='myForm' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='post' >
     <input id='password' type='password' name='password' placeholder='Password'> <span class='error'><?php echo $passwordErr?></span><br/>
     <input id='confirm-password' type='password' name='confpassword' placeholder='Confirm Password'> <span class='error'><?php echo $confPasswordErr?></span><br/>
     <input type='submit' value='Reset Password!' class='green-btn'>
  </form>




<?php
require_once ("user_functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
// storing the key is necessary in order to have it for the reset password function
// as you lose the key from the GET array once you sumbit
if($_SERVER['REQUEST_METHOD'] == 'GET'){
  $key = $_GET['key'];
  setcookie("key", $key);
}

// password requirements checking
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
          // cexecute the password reset code, then clear the cookie
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
</div>
</body>
