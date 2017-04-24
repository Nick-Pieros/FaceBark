<?php
session_start();
require_once ("functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
$user_id = $_COOKIE['user_id'];
$upload = $_SESSION['filename'];

CreatePost($dbh, $user_id, $_POST['title'], $_POST['caption'], GetUploadId($dbh, $upload));

// remove all session variables
session_unset();

// destroy the session
session_destroy();
//only thing I can do right now is go to the gallery since I don't know the post id
header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/gallery.php?page=1")
?>
