<?php
/*
*   create-post.php -- creates a post; posts must have an upload and a title
*   however, captions are optional
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/
  // begin session to retrieve session variable "filename"
  session_start();
  require_once ("functions.php");
  require_once ("connect.php");
  require_once ("user_functions.php");
  $dbh=ConnectDB();

  $user_id = $_COOKIE['user_id']; // logged in user_id
  $upload = $_SESSION['filename']; // filename of the file
  $tags = [];

  $title = strip_tags($_POST['title']); // sanitize title
  $caption = strip_tags($_POST['caption']); // sanitize caption
  $caption = explode(' ', $caption);

  $mailingList = [];

  // for each element in the caption array, if the element contains '@' turn it into a username link
  // if the element contains a '#' turn it into searcheable link
  foreach($caption as &$value){
      if (strpos($value, '@') === 0) {
        // preg_replace needed so as to not include special chars
        array_push($mailingList, substr($value,1));
       $value = str_replace($value, ' <a href="user.php?username=' . preg_replace("/[^a-zA-Z0-9]/", "",substr($value, 1)) . '">' . $value . '</a> ', $value );
    }
    if (strpos($value, '#') === 0) {
      array_push($tags, substr($value,1));
     $value = str_replace($value, ' <a href="search.php?searchTerm=' . preg_replace("/[^a-zA-Z0-9]/", "",substr($value, 1)) . '&method=Tags">' . $value . '</a> ', $value );
   }
  }

  $caption = implode(' ', $caption);

  // create the post in the database
  $post_id = CreatePost($dbh, $user_id, $title, $caption, GetUploadId($dbh, $upload));

  // if it was successful, send out emails to anyone mentioned in the post
  if($post_id != 0){
   SendNotification($dbh, $mailingList, "post", "http://elvis.rowan.edu/~blackc6/awp/FaceBark/post.php?post_id=".$post_id);
  }

  // for each hashtag in the caption, create a new caption if it doesn't
  // already exist
  foreach($tags as &$tag){
    CreateHashtag($dbh, $tag, $post_id, 0);
  }

  // remove all session variables
  session_unset();
  // destroy the session
  session_destroy();
  // redirect to the newly created post
  header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/post.php?post_id=".$post_id);
  die();
?>
