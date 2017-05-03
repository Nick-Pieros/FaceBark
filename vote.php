<!DOCTYPE html>
<?php
/*
*   vote.php -- allows a user to upvote or downvote a post and affect it's popularity
*
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/
require_once ("functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
echo $_GET['post_id'];
echo $_GET['vote'];
VoteOnPost($dbh, $_COOKIE['user_id'], $_GET['post_id'], $_GET['vote']);
//reloads the page
header('Location: '.  $_SERVER['HTTP_REFERER']);
die();
 ?>
