<!DOCTYPE html>
<?php
/*
*   page-not-found.php -- universal 404
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/
?>

<head>
  <meta charset='UTF-8'/>
  <title>404 - Bark Not Found</title>
  <link href='css/basic-format.css' rel='stylesheet'/>
  <link href='images/doggo1.jpg' rel='icon'/>
</head>
<body>
  <div class='menu-container'>
       <div class='menu'>

       </div>
    </div>
  <div class='content'>
    <h1>THE PAGE CANNOT BE FOUND!</h1>
  <br/>
    <img src='images/notfound404.jpg'>
  <br/>
   <div>Would you like to return: <a href="<?php echo  $_SERVER['HTTP_REFERER'] ?>"> to the previous page?</a> or <a href='gallery.php?page=1'>to the gallery?</a></div>
</div>
</body>
