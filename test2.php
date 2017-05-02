<?php
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh=ConnectDB();

    $post_id = CreatePost($dbh, $_COOKIE['user_id'], '1111111111111111111111111111111', 'test', GetUploadId($dbh, 322));
    echo $post_id;
 ?>
