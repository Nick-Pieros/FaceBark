<form action='signin.php' method='post'>
   <input type='text' name='signinuser' placeholder='Username'>
   <input type='password' name='signinpassword' placeholder='Password'>
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!empty($_POST["signinuser"])) { // if field is not empty
      if (!empty($_POST["signinpassword"])) {
        $user = LoginUser($_POST["signinuser"], $_POST["signinpassword"], $dbh);

        //$signinpassword = password_hash($signinpassword, PASSWORD_DEFAULT); //temporarily hash the password to see if it matches, not the correct way to do it, need to fix
        //$tmp_user_id = LoginUser($signinuser, $signinpassword, $dbh);
        if($user->user_id > 1){
          setcookie('user_id', $user->user_id);
          header('Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/profile.php');
          die();
        } else if($user->tmp_user_id > 1){
          setcookie('tmp_user_id', $user->tmp_user_id);
          header('Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/verify-user.php');
          die();
        } else{
          header('Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/signin.php?login=false');
          die();
        }
        //echo $tmp_user_id;
      }
      else{
        echo "fill in password";
      }
    }
    else{
      echo "fill in username.";
    }
    echo '<a href="http://elvis.rowan.edu/~blackc6/awp/FaceBark/registration.php">Home</a>';
 }

 ?>
