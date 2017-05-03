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

  // deletes comment and refreshes page
  DeleteComment($dbh, $_GET['post_id'], $_GET['comment_id'], $_COOKIE['user_id']);
  header('refresh: 0; url=post.php?post_id='.$_GET['post_id']);
  die();
 ?>
