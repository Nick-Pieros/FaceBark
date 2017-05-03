<!DOCTYPE html>
<?php
/*
*   gallery.php -- displays a quick view of the most recent posts, 8 per page
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/
  // perform necessary requires and create database handler
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh=ConnectDB();

  $user_id=$_COOKIE['user_id'];
  $page=$_GET['page'];

  // retrieve userinfo and user posts from the database
  $userinfo=GetUserInfo($user_id, $dbh);
  $galleryPosts=GetRecentPosts($page, 0, $dbh);
  $galleryNext=GetRecentPosts($page+1, 0, $dbh);
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
    <?php include 'header.php'; ?>
    <div class='content'>
      <div class='left-side-items page-wrap'>
        <!-- displays prev only if not on the first page -->
        <?php if($page > 1) :?>
          <a class='sidebar' href='./gallery.php?page=<?php echo ($page-1) ?>'>Prev </a>
        <?php endif; ?>
      </div>
      <div class='post-feed'>
              <?php
              if($galleryPosts==0){
                echo "There's nothing to see here.";
              }
              foreach($galleryPosts as &$post):
              ?>
          <div class='post'>
            <div class='post-left'>
              <div class='post-header'>
                  <h2 class='post-title'><?php echo ($post->post_title) ?></h2>
                  <h4 class='post-poster'>
                    by <a href='./user.php?username=<?php echo ($post->username) ?>'><?php echo ($post->username) ?> </a> on <?php echo $post->post_timestamp?>
                  </h4>
              </div>
              <div class='post-image'>
                <a href='post.php?post_id=<?php echo ($post->post_id) ?>'>  <img src='<?php echo ($post->file_path) ?>'></img></a>
              </div>
            </div>
            <div class='post-right'>
              <div class='post-desc'>
                <p><?php echo ($post->post_text) ?></p>
              </div>
              <div class='post-voting'>
                <div class='post-upvote'>
                  <a href='vote.php?vote=1&post_id=<?php echo $post->post_id?>'><img src='images/like-paw.png'></img></a>
                </div>
                <div class='post-downvote'>
                  <a href='vote.php?vote=-1&post_id=<?php echo $post->post_id?>'><img src='images/dislike-paw.png'></img></a>
                </div>
              </div>
              <div><h4> Popularity: <?php echo $post->post_votes;?></h4></div>
            </div>
          </div>
          <?php endforeach;?> <!-- end of post -->
      </div>
    <div class='right-side-items page-wrap'>
      <?php if($galleryNext!= 0) :?>
        <a class='sidebar' href='./gallery.php?page=<?php echo ($page+1) ?>'>Next </a>
      <?php endif; ?>
    </div>
    </div>
</body>

</html>
