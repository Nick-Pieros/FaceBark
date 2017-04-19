<form action='signin.php' method='post'>
   <input type='text' name='signinuser' placeholder='Username'>
   <input type='password' name='signinpassword' placeholder='Password'>
   <input type='submit' value='Log In' class='green-btn'> <span class='error'><?php echo $loginErr?></span>
</form>
<?php
require_once ("functions.php");
require_once ("connect.php");
$dbh = ConnectDB();
$loginErr = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!empty($_POST["signinuser"])) { // if field is not empty
      if (!empty($_POST["signinpassword"])) {
        $signinuser = $_POST["signinuser"];
        $signinpassword = $_POST["signinpassword"];
        //$signinpassword = password_hash($signinpassword, PASSWORD_DEFAULT); //temporarily hash the password to see if it matches, not the correct way to do it, need to fix
        $user_id = LoginUser($signinuser, $signinpassword, $dbh);
        if($user_id > 1){
          setcookie('user_id', $user_id);
          header('Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/profile.php');
          die();
        } else{
          header('Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/registration.php');
          die();
        }
      }
      else{
        echo "fill in password";
      }
    }
    else{
      echo "fill in user.";
    }
    echo $signinpassword;
 }
 ?>
