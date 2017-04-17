<!DOCTYPE html>
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
                // may not need this
                function confirm() {
                  var x = $('#f_name').val();
                  if (x == '') {
                      alert('First name must be filled out');
                      return false;
                  }
                  x = $('#l_name').val();
                  if (x == '') {
                      alert('Last name must be filled out');
                      return false;
                  }
                  x = $('#username').val();
                  if (x == '') {
                      alert('Username must be filled out');
                      return false;
                  }
                  x = $('#email').val();
                  if (x == '') {
                      alert('Email must be filled out');
                      return false;
                  }
                  x = $('#password').val();
                  if (x == '') {
                      alert('Password must be filled out');
                      return false;
                  }
                  var y = $('#confirm-password').val();
                  if (y != x) {
                      alert('Passwords must match.');
                      return false;
                  }
                }
      </script>
   </head>
   <body>
      <div class='menu-container'>
         <div class='menu'>
            <div class='date'></div>
            <div class='login'>
               <form action='/action_page.php'>
                  <input type='text' name='email' placeholder='Email'>
                  <input type='password' name='password' placeholder='Password'>
                  <input type='submit' value='Log In'>
               </form>
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
               <h1>Sign up for free!</h1>
               <?php
               require_once ("functions.php");
               require_once ("connect.php");
               $dbh = ConnectDB();
               $unameErr = $emailErr = $fnameErr = $lnameErr = $passwordErr = "";
               $username  = $email = $f_name = $l_name = $password = "";

               if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  if (empty($_POST["username"])) {
                      $unameErr = "*Name is required";
                  } else {
                      $username = test_input($_POST["username"]);
                  }

                  if (empty($_POST["email"])) {
                      $emailErr = "*Email is required";
                  } else {
                      $email = test_input($_POST["email"]);
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

                  if (empty($_POST["password"])) {
                      $passwordErr = "*Password is required";
                  } else {
                      $password = $_POST["password"];
                  }
                  if(($password != "") && ($username != "") && ($email != "") && ($f_name != "") && ($l_name != "")){
                   $user_id = RegisterUser($username, $email, $f_name, $l_name, $password, $dbh);
                   if(!($user_id > 1)){
                     $userExistsErr = "<span class='error'> User already exists, please log in! </span>";
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
               <form name='myForm' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='post'>
                  <input id='f_name' type='text' name='f_name' placeholder='First Name' value="<?php echo $_POST['f_name']?>"> <span class='error'><?php echo $fnameErr?></span><br/>
                  <input id='l_name' type='text' name='l_name' placeholder='Last Name' value="<?php echo $_POST['l_name']?>"> <span class='error'><?php echo $lnameErr?></span><br/>
                  <input id='username' type='text' name='username' placeholder='Username' value="<?php echo $_POST['username']?>"> <span class='error'><?php echo $unameErr?></span> <br/>
                  <input id='email' type='text' name='email' placeholder='Email' value="<?php echo $_POST['email']?>"> <span class='error'><?php echo $emailErr?></span><br/>
                  <input id='password' type='password' name='password' placeholder='Password'> <span class='error'><?php echo $passwordErr?></span><br/>
                  <input id='confirm-password' type='password' name='confirm-password' placeholder='Confirm Password'><br/>
                  <input type='submit' value='Sign Up!'>
               </form>
               <?php echo $userExistsErr; ?>
            </div>
            <div class='forgotpass'>
               <h1>Forgot password?</h1>
               <form action='/action_page.php'>
                  <input type='text' name='email' placeholder='Email'><br>
                  <input type='submit' value='Reset Password!'>
               </form>
            </div>
         </div>
      </div>
   </body>
</html>
