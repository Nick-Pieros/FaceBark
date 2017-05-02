<!DOCTYPE html>
<?php
require_once ("functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
echo " ". IsAdminUser($dbh, 3);
 ?>
