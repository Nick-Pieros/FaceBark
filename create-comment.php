
<?php

  require_once ("functions.php");
  require_once ("connect.php");
  $dbh=ConnectDB();
  $post_id = $_POST['post_id'];

  $new_comment = strip_tags($_POST['new_comment']);
  $new_comment = explode(" ", $new_comment); //turn new comment into an array of strings delimited by whitespace

  // for each element in the comment array, if the element contains '@' replace the first "@" with
  foreach($new_comment as &$value){
      if (strpos($value, '@') === 0) {
       $value = str_replace($value, ' <a href="user.php?username=' . preg_replace("/[^a-zA-Z0-9]/", "",substr($value, 1)) . '">' . $value . '</a> ', $value ); // preg_replace needed so as to not include special chars
    }
    if (strpos($value, '#') === 0) {
     $value = str_replace($value, ' <a href="search.php?searchTerm=' . preg_replace("/[^a-zA-Z0-9]/", "",substr($value, 1)) . '&method=Tags">' . $value . '</a> ', $value );
     CreateHashtag($dbh, substr($value,1), $post_id, 0);
   }
  }
  echo CreateComment($dbh, $post_id, $_COOKIE['user_id'], implode(" ", $new_comment), 0);
?>
