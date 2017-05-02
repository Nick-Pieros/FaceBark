<!DOCTYPE html>
<?php // prepare the profile information
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
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'upload2.php';
    if($filepath){
      $photo_uploaded = 1;
    }
  }
  if($filepath){ // if it has a filepath, it has a name
    $_SESSION['filename'] = $filename; //$filename is taken from upload2
  }
?>
<html lang='en'>

<head>
    <meta charset='UTF-8' />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>Facebark</title>
    <link href='css/header.css' rel='stylesheet' />
    <link href='css/post.css' rel='stylesheet' />
    <link href='css/profile.css' rel='stylesheet' />
    <style>
      #title{
      	font-size:20px;
      	font-family:Courier, monospace;
      	height:28px;
      	font-weight:bold;
      	width:100%;
        border-radius: 2px;
        box-shadow:  0px 2px 11px 0px rgba(0, 0, 0, 0.3);
        border: 1px solid #e2e6e6;
        margin: 10px 0 10px 0;
        outline: none;
      }
      #caption {
       border-radius: 2px;
       box-shadow:  0px 2px 11px 0px rgba(0, 0, 0, 0.3);
       border: 1px solid #e2e6e6;
       margin: 10px 0 10px 0;
       font-family: 'Open Sans', sans-serif;
       outline: none;
       width: 100%;
       height: 150px;
      }
      .content{
        height:100vh;
      }
      .create-post-info{
        width:75%;
        display:flex;
        flex-direction: column;
        align-items: center;
      }
      .upload{
        width:600px;
      }
    </style>
    <link href='images/doggo1.jpg' rel='icon' />
</head>

<body>
    <?php include 'header.php';?>
    <div class='content' >
      <div class='create-post-info.php'>
        <?php if($photo_uploaded == 0):?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        	Image upload: <input type="file" name="upfile" size="25" />
        	<input type="submit" name="submit" value="Next!" />
        </form>
        <br/>
      <?php else:?>
      <form id='postform' name='post-form' action='create-post.php' method='post'>
          <input id='title' type='text' name='title' placeholder='Give your post a title' ><br/>
          <div class='upload-div'><img class='upload'src='<?php echo $filepath?>'></img></div><br/>
          <textarea id='caption' type='text' name='caption' form='postform' maxlength='512' placeholder='Add a description #tags and @mention'></textarea><br/>
          <input type='submit' value='Submit post!'>
      </form>
    <?php endif?>
    </div>
    </div>
</body>

</html>
