<!DOCTYPE html>
<?php
// prepare the profile information
require_once ("functions.php");
require_once ("connect.php");
$dbh = ConnectDB();
$user_id = $_COOKIE['user_id'];
$userinfo = GetUserInfo($user_id, $dbh);
$doggoinfo = GetDogInfo($user_id, $dbh);
$doggoPosts = GetRecentPosts(1, $user_id, $dbh);
?>
<html lang='en'>

<head>
    <meta charset='UTF-8' />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>Facebark</title>
    <link href='css/header.css' rel='stylesheet' />
    <link href='css/post.css' rel='stylesheet' />
    <link href='css/profile.css' rel='stylesheet' />
    <link href='images/doggo1.jpg' rel='icon' />
</head>

<body>
    <?php
      include 'header.php';
      print_r($doggoPosts[0]);
    ?>
    <div class='content'>
      <div class='profile-info'>
        <div class='profile-name'>
          <h2>
          <!-- this content will change using php -->
          <?php echo ($userinfo[0]->username)?>
        </h2>
      </div>
        <div class='profile-pic'>
          <img src='<?php echo ($doggoinfo[0]->file_path)?>'></img>
        </div>
        <div class='about-me'>
          <div class='doggo-desc'>
            <h5>Name:</h5>
          <br/>
            <h5>Breed:</h5>
            <br/>
            <h5>Weight:</h5>
            <br/>
            <h5>Bio:</h5>
        </div>
        <div class='doggo-facts'>
            <h5><?php echo ($doggoinfo[0]->dog_name)?></h5>
            <br/>
            <h5><?php echo ($doggoinfo[0]->dog_breed)?></h5>
            <br/>
            <h5><?php echo ($doggoinfo[0]->dog_weight)?></h5>
            <br/>
            <h5><?php echo ($doggoinfo[0]->dog_bio)?></h5>
        </div>
        </div>
      </div>
      <div class='post-feed'>

        <div class='post'>
          <div class='post-left'>
            <div class='post-header'>
                <h2 class='post-title'>
                  <!-- this content will change using php -->
                  <?php echo ($doggoPosts[0]->post_title) ?>
               </h2>
                <h4 class='post-poster'>
                  <!-- this content will change using php -->
                  by <a href='./profile.html'><?php echo ($doggoPosts[0]->username) ?> </a>
               </h4>
            </div>
            <div class='post-image'>
                <!-- this content will change using php -->
                <a href='post.php?post_id=<?php echo ($doggoPosts[0]->post_id) ?>'><img src='<?php echo ($doggoPosts[0]->file_path) ?>'></img></a>
            </div>
          </div>
        <div class='post-right'>
            <div class='post-desc'>
                <p>
                    <!-- this content will change using php -->
                    <?php echo ($doggoPosts[0]->post_text) ?>
                </p>
            </div>
            <div class='post-voting'>
                <div class='post-upvote'>
                    <img src='images/like-paw.png'></img>
                </div>
                <div class='post-downvote'>
                    <img src='images/dislike-paw.png'></img>
                </div>
            </div>
          </div>
        </div>

      </div>
      <div class='right-side-items'>

      </div>
  </div>
</body>

</html>
