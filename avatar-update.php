<?php
  include 'upload2.php'; //perform the necessary includes, and file upload2
  // set the avatar as the new upload
  $doggoInfo = GetDogInfo($user_id, $dbh)[0];
  $upload_id = GetUploadId($dbh, $filename);
  UpdateDogInfo($dbh, $doggoInfo->dog_name, $doggoInfo->dog_breed, $doggoInfo->dog_weight, $doggoInfo->dog_bio, $user_id, $upload_id);
  header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/profile.php");
  die();
 ?>
