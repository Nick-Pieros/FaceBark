<?php
  // prepare the profile information
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh = ConnectDB();
  $user_id = $_COOKIE['user_id'];
  $doggoInfo = GetDogInfo($user_id, $dbh)[0];
  //weight isn't working for some reason
  $name = strip_tags($_POST['name']);
  $breed = strip_tags($_POST['breed']);
  $weight = strip_tags($_POST['weight']);
  $bio = trim(strip_tags($_POST['bio']));
  UpdateDogInfo($dbh, $name, $breed, $weight, $bio, $user_id, GetUploadID($dbh, $doggoInfo->file_name));
  header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/profile.php");
  die();
?>
