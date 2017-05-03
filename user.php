<!DOCTYPE html>


<?php
/*
*   user.php -- when provided with a user name, will bring the user to their
*   page
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/

// prepare the profile information
require_once ("functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
$username=$_GET['username'];
$page = $_GET['page']; //page number of the posts

// if no page number is indicated when requesting the page,
// start at page 1 of the doggo posts
if(!isset($_GET['page'])){
  $page = 1;
}

$user_id=GetUserByUsername($dbh, $username);

if($user_id== 0){// if user doens't exist, 404
  header('Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/page-not-found.php');
  die();
}

$userinfo=GetUserInfo($user_id,$dbh);
$doggoinfo=GetDogInfo($user_id, $dbh);
$doggoPosts=GetRecentPosts($page, $user_id, $dbh);
$doggoNext=GetRecentPosts($page+1, $user_id, $dbh);
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
    <?php include 'header.php';?>
    <div class='content'>
        <div class='profile-info'>
            <div class='profile-name'>
                <h2>
          <?php echo ($userinfo[0]->username)?>
        </h2>
            </div>
            <div class='profile-pic'>
                <img src='<?php echo ($doggoinfo[0]->file_path)?>'></img>
            </div>
            <div class='profile-update'>
              <?php if(IsAdminUser($dbh, $_COOKIE['user_id'])):?> <!-- if admin, can change the water -upload.php-->
                <div class='avatar-upload'>
                    <?php include 'avatar-upload.php';?>
                </div>
              <?php endif?>
            </div>
            <div class='about-me'>
              <div class='doggo-facts'>
                  <?php if(isset($_GET['edit']) && IsAdminUser($dbh, $_COOKIE['user_id'])):?>
                    <form id='bioform' name='bio-form' action='update-bio.php' method='post'>
                        <label for='name'>Name:</label>
                        <input id='name' type='text' name='name' placeholder='Doggo name here!' maxlength='30' value="<?php echo ($doggoinfo[0]->dog_name)?>"><br>
                        <label for='breed'>Breed:</label>
                        <input id='breed' type='text' name='breed' placeholder='Dogge breed here!' maxlength='30' value="<?php echo ($doggoinfo[0]->dog_breed)?>"><br/>
                        <label for='weight'>Weight:</label>
                        <input id='weight' type='text' name='weight' placeholder='Doggo weight hear!' maxlength='4' value="<?php echo ($doggoinfo[0]->dog_weight)?>"> lbs<br/>
                        <label for='bio'>Bio:</label>
                        <textarea id='bio' type='text' name='bio' placeholder='Doggo bio here!' form='bioform' maxlength='250'><?php echo ($doggoinfo[0]->dog_bio)?></textarea><br/>
                        <input type="hidden" value="<?php echo ($userinfo[0]->user_id)?>" name="profile_id">
                        <input type="hidden" value="<?php echo ($userinfo[0]->username)?>" name="username">
                        <input type='submit' value='Update bio!'>
                    </form>
                  <?php endif ?>
                  <?php if(!isset($_GET['edit'])):?>
                    <table>
                      <tr>
                        <td>Name:</td>
                        <td><?php echo ($doggoinfo[0]->dog_name)?></td>
                      </tr>
                      <tr>
                        <td>Breed:</td>
                        <td><?php echo ($doggoinfo[0]->dog_breed)?></td>
                      </tr>
                      <tr>
                        <td>Weight:</td>
                        <td><?php echo ($doggoinfo[0]->dog_weight)?> lbs</td>
                      </tr>
                      <tr>
                        <td>Bio:</td>
                        <td><?php echo ($doggoinfo[0]->dog_bio)?></td>
                      </tr>
                    </table>
                <?php endif?>
                </div>


            </div>
            <?php  if(!isset($_GET['edit']) && IsAdminUser($dbh, $_COOKIE['user_id'])):?>
            <form action="user.php" method='get'>
              <input type="submit" value="Edit" name='edit'/>
              <input type="hidden" value="<?php echo ($userinfo[0]->username)?>" name="username">
            </form>
            <?php endif ?>
        </div>
        <div class='post-feed'>
            <?php // if a doggo hasn 't made any posts, display this
        if($doggoPosts == 0){
          echo "Nothing to see here. Go out there and bark!";
        }
        foreach($doggoPosts as &$post):?> <!-- display the dog posts's information-->
        <div class='post '>
          <div class='post-left '>
            <div class='post-header '>
                <h2 class='post-title '>
                  <?php echo ($post->post_title) ?>
               </h2>
                <h4 class='post-poster '>
                  by <a href='./user.php?username=<?php echo ($post->username) ?>'><?php echo ($post->username) ?> </a>
               </h4>
            </div>
            <?php if($post->file_path): // link to posts with no image?>
            <div class='post-image'>
              <a href='post.php?post_id=<?php echo ($post->post_id) ?>'>  <img src='<?php echo ($post->file_path) ?>'></img></a>
            </div>
          <?php else: ?>
            <a href='post.php?post_id=<?php echo ($post->post_id) ?>'>
            <div class='post-image'>
            </div>
            </a>
          <?php endif; ?>
    </div>
    <div class='post-right'>
        <div class='post-desc'>
            <p>
                <?php echo ($post->post_text) ?>
            </p>
        </div>
        <div class='post-voting'>
           <div class='post-upvote'>
              <a href='vote.php?vote=1&post_id=<?php echo $post->post_id?>'><img src='images/like-paw.png'></img></a>
           </div>
           <div class='post-downvote'>
              <a href='vote.php?vote=-1&post_id=<?php echo $post->post_id?>'><img src='images/dislike-paw.png'></img></a>
           </div>
        </div>
        <div><h4>Popularity: <?php echo $post->post_votes;?></h4></div>
    </div>
    </div>
    <?php endforeach;?>
    </div>
    <div class='right-side-items page-wrap'> <!-- for navigating between pages-->
      <!-- page navigation -->
      <?php if($page > 1) :?>
        <a class='sidebar' href='./user.php?page=<?php echo ($page-1) ?>&username=<?php echo $username ?>'>Prev </a>
      <?php endif; ?>
        <br/>
      <?php if($doggoNext!= 0) :?>
        <a class='sidebar'  href='./user.php?page=<?php echo ($page+1) ?>&username=<?php echo $username ?>'>Next </a>
      <?php endif; ?>

    </div>
    </div>
</body>

</html>
