<!DOCTYPE html>
<?php
require_once ("functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
echo $_GET['post_id'];
echo $_GET['vote'];
VoteOnPost($dbh, $_COOKIE['user_id'], $_GET['post_id'], $_GET['vote']);
header('Location:  http://elvis.rowan.edu/~blackc6/awp/FaceBark/post.php?post_id='.$_GET['post_id']);
die();
 ?>
