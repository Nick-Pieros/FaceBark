<!DOCTYPE html>
<?php
/*
*   ui-create-post.php -- php used to select the upload and see what your post
*   would look like once uploaded
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/
  session_start();
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh=ConnectDB();

  $user_id=$_COOKIE['user_id'];
  $photo_uploaded = 0;

  if($user_id<1){
    header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/registration.php");
    die();
  }
  $userinfo=GetUserInfo($user_id, $dbh);

?>
<html lang='en'>

<head>
    <meta charset='UTF-8' />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>Facebark</title>
    <link href='css/header.css' rel='stylesheet' />
    <link href='css/post.css' rel='stylesheet' />
    <link href='css/profile.css' rel='stylesheet' />
    <link href='css/uploads.css' rel='stylesheet' />
    <link href='images/doggo1.jpg' rel='icon' />
    <script type='text/javascript'>
    function nospaces(t){
      if(t.value.match(/\n/)){
        t.value=t.value.replace(/\n/g,' ');
      }
    }
    </script>
</head>

<body>
    <?php include 'header.php';?>
    <div class='content' >
      <div class='create-post-info.php'>
        <div class='file-upload-results'>
        <?php
          // if a file has been uploaded successfully
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include 'upload2.php';
            if($filepath){
              $photo_uploaded = 1;
            }
          }
          if($filepath){ // if it has a filepath, it has a name
            $_SESSION['filename'] = $filename; //$filename is taken from upload2
          }?>
        </div>
        <?php if($photo_uploaded == 0):?> <!-- -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        	Image upload: <input type="file" name="upfile" size="25" />
        	<input type="submit" name="submit" value="Next!" />
        </form>
        <br/>
      <?php else:?>
      <form id='postform' name='post-form' action='create-post.php' method='post'>
          <input id='title' type='text' name='title' maxlength='144' placeholder='Give your post a title' pattern=".{1,}" required title="Minimum 1 characters required"><br/>
          <div class='upload-div'><img class='upload'src='<?php echo $filepath?>'></img></div><br/>
          <textarea id='caption' type='text' name='caption' form='postform' maxlength='512' onkeyup="nospaces(this)" placeholder='Add a description #tags and @mention'></textarea><br/>
          <input type='submit' value='Submit post!'>
      </form>
    <?php endif; ?>
    </div>
    </div>
</body>

</html>
