<!DOCTYPE html>
<?php // prepare the profile information
require_once ("functions.php");
require_once ("connect.php");
$dbh=ConnectDB();
$user_id=$_COOKIE['user_id'];
if($user_id<1 || $user_id == null){
  header("Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/registration.php");
  die();
}
$userinfo=GetUserInfo($user_id, $dbh);
$doggoinfo=GetDogInfo($user_id, $dbh);
$doggoPosts=GetRecentPosts(1, $user_id, $dbh);
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
    <?php include 'header.php';  ?>
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
                <div class='avatar-upload'>
                    <?php include 'avatar-upload.php';?>
                </div>
            </div>
            <div class='about-me'>
              <?php if(!isset($_GET['edit'])):?>
              <div class='doggo-desc'>
                <h5>Name:</h5>
                <br/>
                <h5>Breed:</h5>
                <br/>
                <h5>Weight:</h5>
                <br/>
                <h5>Bio:</h5>
            </div>
          <?php endif?>
              <div class='doggo-facts'>
                  <?php if(isset($_GET['edit'])):?>
                    <form id='bioform' name='bio-form' action='update-bio.php' method='post'>
                        <label for='name'>Name:</label>
                        <input id='name' type='text' name='name' placeholder='Doggo name here!' maxlength='30' value="<?php echo ($doggoinfo[0]->dog_name)?>"><br>
                        <label for='breed'>Breed:</label>
                        <input id='breed' type='text' name='breed' placeholder='Dogge breed here!' maxlength='30' value="<?php echo ($doggoinfo[0]->dog_breed)?>"><br/>
                        <label for='weight'>Weight:</label>
                        <input id='weight' type='text' name='weight' placeholder='Doggo weight hear!' maxlength='4' value="<?php echo ($doggoinfo[0]->dog_weight)?>"> lbs<br/>
                        <label for='bio'>Bio:</label>
                        <textarea id='bio' type='text' name='bio' placeholder='Doggo bio here!' form='bioform' maxlength='256'><?php echo ($doggoinfo[0]->dog_bio)?></textarea><br/>
                        <input type='submit' value='Update bio!'>
                    </form>
                  <?php endif ?>
                  <?php if(!isset($_GET['edit'])):?>
                  <h5><?php echo ($doggoinfo[0]->dog_name)?></h5>
                  <br/>
                  <h5><?php echo ($doggoinfo[0]->dog_breed)?></h5>
                  <br/>
                  <h5><?php echo ($doggoinfo[0]->dog_weight)?> lbs</h5>
                  <br/>
                  <h5><?php echo ($doggoinfo[0]->dog_bio)?></h5>
                <?php endif?>
                </div>


            </div>
            <?php  if(!isset($_GET['edit'])):?>
            <form action="profile.php?edit=1" >
              <input type="submit" value="Edit" name='edit'/>
            </form>
          <?php endif ?>
        </div>
        <div class='post-feed'>
          <a href='./ui-create-post.php'>Create a post here!</a>
            <?php // if a doggo hasn't made any posts, display this
        if($doggoPosts == 0){
          echo "Nothing to see here. Go out there and bark!";
        }
        foreach($doggoPosts as &$post):?>
        <div class='post '>
          <div class='post-left '>
            <div class='post-header '>
                <h2 class='post-title '>
                  <?php echo ($post->post_title) ?>
               </h2>
                <h4 class='post-poster '>
                  by <a href='./user.php?username=<?php echo ($post->username) ?>'><?php echo ($post->username) ?> </a>
                  on <?php echo ($post->post_timestamp) ?>
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
                <!-- this content will change using php -->
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
        <div>
        <h4>
          Popularity: <?php echo $post->post_votes;?>
        </h4>
      </div>
    </div>
    </div>
    <?php endforeach;?>
    </div>
    <div class='right-side-items'>

    </div>
    </div>
</body>

</html>
