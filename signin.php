
<?php
/*
*   signin.php -- provides the display for signing in a user
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/

 if(isset($_POST['signinuser']) || isset($_GET['login'])): ?>
  <link href='css/basic-format.css' rel='stylesheet'/>
  <div class='menu-container'>
       <div class='menu'>
         <div class='logo'><a href='registration.php'><img src='images/fbark-logo.png' /></a></div>
       </div>
    </div>
  <div class='content'>
    <h3>Please sign in!</h3>
<?php endif; ?>
<form action='signin.php' method='post'>
   <input type='text' name='signinuser' placeholder='Username'><?php  if(isset($_POST['signinuser'])|| isset($_GET['login'])) echo '<br/>' ?>
   <input type='password' name='signinpassword' placeholder='Password'><?php  if(isset($_POST['signinuser'])|| isset($_GET['login'])) echo '<br/>' ?>
   <input type='submit' value='Log In' class='green-btn'> <span class='error'><?php echo $loginErr?></span>
</form>

<?php
require_once ("user_functions.php");
require_once ("connect.php");
$dbh = ConnectDB();
$loginErr = '';
if ($_GET['login'] == 'false'){
  echo "Login information was incorrect. Please double check your input.";
}
// if a request is made with all 3 of these variables checked, then someone wishes to sign in
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signinuser']) && isset($_POST['signinpassword']) ) {
  if (!empty($_POST["signinuser"])) { // if field is not empty
      if (!empty($_POST["signinpassword"])) {
        $user = LoginUser($_POST["signinuser"], $_POST["signinpassword"], $dbh);


          // if correct log in and registered user, go to profile
        if($user->user_id > 1){
          setcookie('user_id', $user->user_id);
          header('Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/profile.php');
          die();
        } else if($user->tmp_user_id > 1){// if correct log in but not verified, resend email to verify
          setcookie('tmp_user_id', $user->tmp_user_id);
          header('Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/verify-user.php');
          die();
        } else{// else try to sign in again
          header('Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/signin.php?login=false');
          die();
        }
      }
      else{
        echo "Fill in password";
      }
    }
    else{
      echo "Fill in username.";
    }
 }

 ?>
 <?php  if(isset($_POST['signinuser'])): ?>
 </div>
 </body>
 <?php endif ?>
