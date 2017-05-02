<?php
  session_start();
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh=ConnectDB();
  $user_id = $_COOKIE['user_id'];
  $upload = $_SESSION['filename'];
  $tags = [];
  if($upload == 0){
    $upload = null;
  }
  $caption = strip_tags($_POST['caption']);
  $caption = explode(" ", $caption);
  $title = strip_tags($_POST['title']);

  // for each element in the caption array, if the element contains '@' turn it into a username link
  // if the element contains a '#' turn it into searcheable link
  foreach($caption as &$value){
      if (strpos($value, '@') === 0) {
        // preg_replace needed so as to not include special chars
       $value = str_replace($value, ' <a href="user.php?username=' . preg_replace("/[^a-zA-Z0-9]/", "",substr($value, 1)) . '">' . $value . '</a> ', $value );
    }
    if (strpos($value, '#') === 0) {
      array_push($tags, substr($value,1));
     $value = str_replace($value, ' <a href="search.php?searchTerm=' . preg_replace("/[^a-zA-Z0-9]/", "",substr($value, 1)) . '&method=Tags">' . $value . '</a> ', $value );
   }
  }
  $caption = implode(' ', $caption);

  $post_id = CreatePost($dbh, $user_id, $title, $caption, GetUploadId($dbh, $upload));
  foreach($tags as &$tag){
    CreateHashtag($dbh, $tag, $post_id, 0);
  }

  // remove all session variables
  session_unset();
  // destroy the session
  session_destroy();
  //only thing I can do right now is go to the gallery since I don't know the post id
  header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/post.php?post_id=".$post_id);
  die();
?>
