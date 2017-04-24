<!DOCTYPE html>
<?php
require_once ("functions.php");
require_once ("connect.php");
$dbh = ConnectDB();
setcookie ("user_id", "", 1);
unset($_COOKIE['user_id']);
?>
<html lang='en'>
   <head>
      <meta charset='UTF-8'/>
      <title>Facebark - Login</title>
      <link href='css/login.css' rel='stylesheet'/>
      <link href='images/doggo1.jpg' rel='icon'/>
      <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
      <script type='text/javascript'>
         /*
           this code snippet was found online at:
           http://stackoverflow.com/questions/1531093/how-do-i-get-the-current-date-in-javascript
         */
               function getDate() {
                 var today = new Date();
                 var dd = today.getDate();
                 var mm = today.getMonth()+1; //January is 0!
                 var yyyy = today.getFullYear();

                 if(dd<10) {dd='0'+dd}
                 if(mm<10) {mm='0'+mm}
                 today = mm+'/'+dd+'/'+yyyy;
                   $('.date').text(today)
                }
                window.onload = getDate;
      </script>
   </head>
   <body>
      <div class='menu-container'>
         <div class='menu'>
            <div class='date'></div>
            <div class='login'>
               <?php
                //if check cookie and user is signed in do this, else include signin.php
                if(isset($_COOKIE['user_id'])){
                  echo '<h3> Welcome, ' . $_COOKIE['user_id'] . '! </h3>';
                  print_r($_COOKIE);
                } else {
                  include 'signin.php';
                }
                ?>
            </div>
         </div>
      </div>
      <div class='header'>
         <div class='header'>
            <div class='logo'><img src='images/fbark-logo.png'/></div>
         </div>
      </div>
      <div class='photo-signup'>
         <div class='photo-grid'>
               <div class='photo-grid-item first-item'>
                  <img src='images/doggo1.jpg'/>
               </div>
               <div class='photo-grid-item'>
                  <img src='images/doggo2.jpg'/>
               </div>
               <div class='photo-grid-item'>
                  <img src='images/doggo3.jpg'/>
               </div>
               <div class='photo-grid-item'>
                  <img src='images/doggo4.jpg'/>
               </div>
               <div class='photo-grid-item last-item'>
                  <img src='images/doggo5.jpg'/>
            </div>
         </div>
         <div class='signup-forgotpass'>
            <div class='signup'>
               <?php

               $unameErr = $emailErr = $fnameErr = $lnameErr = $passwordErr = $confPasswordErr = "";
               $username  = $email = $f_name = $l_name = $password = "";

               if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  if (empty($_POST["username"])) { // if field is empty
                      $unameErr = "*Name is required";
                  } else {
                      $username = test_input($_POST["username"]);
                  }
                  if (empty($_POST["email"])) {
                      $emailErr = "*Email is required";
                  } else {
                    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) === false) {
                      $email = test_input($_POST["email"]);
                    } else {
                      $emailErr = '*Not a valid address';
                    }
                  }
                  if (empty($_POST["f_name"])) {
                      $fnameErr = "*First name is required";
                  } else {
                      $f_name = test_input($_POST["f_name"]);
                  }

                  if (empty($_POST["l_name"])) {
                      $lnameErr = "*Last name is required";
                  } else {
                      $l_name = test_input($_POST["l_name"]);
                  }
                  // need to perform security actions on password
                  if (empty($_POST["password"])) {
                      $passwordErr = "*Password is required";
                  } else {
                    if (empty($_POST["confpassword"])) {
                        $confPasswordErr = "*Confirm password";
                    } else {
                      if ($_POST["password"] == $_POST["confpassword"]){ // if passwords match, set password
                        $password = $_POST["password"];
                      } else{
                        $confPasswordErr = "*Passwords do not match!";
                      }
                      // else send error message
                    }
                  }
                  if(($password != "") && ($username != "") && ($email != "") && ($f_name != "") && ($l_name != "")){
                   // $password = password_hash($password, PASSWORD_DEFAULT);
                   $user_id = RegisterUser($username, $email, $password, $f_name, $l_name, $dbh);
                   setcookie("user_id", $user_id);
                   if(!($user_id > 1)){
                     $userExistsErr = "<span class='error'> User already exists, please log in! </span>";
                   } else {
                     header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/verify-user.php");
                   }
                 }
               }

               function test_input($data) {
                 $data = trim($data);
                 $data = stripslashes($data);
                 $data = htmlspecialchars($data);
                 return $data;
               }
               ?>
               <h1>Sign up for free!</h1>
               <form name='myForm' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='post' >
                  <input id='username' type='text' name='username' placeholder='Username' value="<?php echo $_POST['username']?>"> <span class='error'><?php echo $unameErr?></span> <br/>
                  <input id='email' type='text' name='email' placeholder='Email' value="<?php echo $_POST['email']?>"> <span class='error'><?php echo $emailErr?></span><br/>
                  <input id='f_name' type='text' name='f_name' placeholder='First Name' value="<?php echo $_POST['f_name']?>"> <span class='error'><?php echo $fnameErr?></span><br/>
                  <input id='l_name' type='text' name='l_name' placeholder='Last Name' value="<?php echo $_POST['l_name']?>"> <span class='error'><?php echo $lnameErr?></span><br/>
                  <input id='password' type='password' name='password' placeholder='Password'> <span class='error'><?php echo $passwordErr?></span><br/>
                  <input id='confirm-password' type='password' name='confpassword' placeholder='Confirm Password'> <span class='error'><?php echo $confPasswordErr?></span><br/>
                  <input type='submit' value='Sign Up!' class='green-btn'>
               </form>
               <?php echo $userExistsErr; ?>
            </div>
            <div class='forgotpass'>
               <h1>Forgot password?</h1>
               <form action=''>
                  <input type='text' name='email' placeholder='Email'><br>
                  <input type='submit' value='Reset Password!' class='green-btn' >
               </form>
            </div>
         </div>
      </div>
   </body>
</html>
