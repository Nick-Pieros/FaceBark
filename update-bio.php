<?php
/*
*   update-bio.php -- php used to change your dog's info
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/

  // prepare the profile information
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh = ConnectDB();
  $user_id = $_COOKIE['user_id'];

  //if you are an administrator, you will have the right to edit
  // any post
  if(IsAdminUser($dbh, $user_id) && isset($_POST['profile_id']) ){
    $user_id = $_POST['profile_id'];
  }

  $doggoInfo = GetDogInfo($user_id, $dbh)[0];
  $name = strip_tags($_POST['name']);
  $breed = strip_tags($_POST['breed']);
  $weight = abs(strip_tags($_POST['weight']));
  $bio = trim(strip_tags($_POST['bio']));
  // get the dog info to populate the bio, which will be sent back along with
  // any info that was changed. avatar uploads are done seperately.
  UpdateDogInfo($dbh, $name, $breed, $weight, $bio, $user_id, GetUploadID($dbh, $doggoInfo->file_name));

  if($_COOKIE['user_id'] != $user_id){
    // if it was an admin editing the page, then user_id will not match the
    // logged in id, so redirect back to the page edit
    header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/user.php?username=".$_POST['username']);
  } else{
    // otherwise the person was editing their own page, so redirect back to profile
    header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/profile.php");
  }
  die();
?>
