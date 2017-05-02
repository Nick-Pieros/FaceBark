<!DOCTYPE html>
<?php
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh=ConnectDB();
  echo "Your comment has been deleted, you will be redirected momentarily.";
  DeleteComment($dbh, $_GET['post_id'], $_GET['comment_id'], $_COOKIE['user_id']);
  header('refresh: 3; url=post.php?post_id='.$_GET['post_id']);
  die();
 ?>
