<!DOCTYPE html>
<?php
/*
*   delete-comment.php -- calls function that replaces comment data with [deleted]
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh=ConnectDB();

  $user_id = $_COOKIE['user_id'];
  $post_id = $_GET['post_id'];

  echo "Your post has been deleted. You will be redirected momentarily.";
  DeletePost($dbh, $post_id, $user_id);
  header('refresh: 0; url=gallery.php?page=1');
  die();
 ?>
