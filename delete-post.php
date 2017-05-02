<!DOCTYPE html>
<?php
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh=ConnectDB();
  $user_id = $_COOKIE['user_id'];
  $post_id = $_GET['post_id'];
  echo "Your post has been deleted. You will be redirected momentarily.";
  echo $post_id . $user_id;
  DeletePost($dbh, $post_id, $user_id);
  header('refresh: 3; url=gallery.php?page=1');
  die();
 ?>
