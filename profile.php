<!DOCTYPE html>
<?php
require_once ("functions.php");
require_once ("connect.php");
$dbh = ConnectDB();
$doggoinfo
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
    ?>
    <div class='content'>
      <div class='profile-info'>
        <div class='profile-name'>
          <h2>
          <!-- this content will change using php -->
          CutestPuppyEvR!
        </h2>
      </div>
        <div class='profile-pic'>
          <img src='images/seamus_profilePic.jpg'></img>
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
            <h5>Seamus</h5>
            <br/>
            <h5>Shihpoo</h5>
            <br/>
            <h5>21 Pounds</h5>
            <br/>
            <h5>Seamus Kane Mullen is one of the family! He loves long walks around the block and loves to bark at squirrels! You can find him in the Mullen household leisurely laying around cuddling with whoever is closest to him. He knows a few keywords such as “You wana go for a walk!?”and “Excuse Me”.</h5>
        </div>
        </div>
      </div>
      <div class='post-feed'>
        <div class='post'>
          <div class='post-left'>
            <div class='post-header'>
                <h2 class='post-title'>
                  <!-- this content will change using php -->
                  I must be hungry, but these puppies look like fried chicken.
               </h2>
                <h4 class='post-poster'>
                  <!-- this content will change using php -->
                  by <a href='./profile.html'>CutestPuppyEvR</a>
               </h4>
            </div>
            <div class='post-image'>
                <!-- this content will change using php -->
                <a href='post-page_1.html'><img src='images/friedChicken.png'></img></a>
            </div>
          </div>
        <div class='post-right'>
            <div class='post-desc'>
                <p>
                    <!-- this content will change using php -->
                    These puppies are adorable! They look just like fried chicken!
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
        <div class='post'>
            <div class='post-header'>
                <h2 class='post-title'>
                  <!-- this content will change using php -->
                  Look at our cute puppies... LOOK AT THEM!
               </h2>
                <h4 class='post-poster'>
                  <!-- this content will change using php -->
                  by <a href='./profile.html'>CutestPuppyEvR</a>
               </h4>
            </div>
            <div class='post-image'>
                <!-- this content will change using php -->
                <a href='post-page_2.html'><img src='images/puppiesFeeding.jpg'></img></a>
            </div>
            <div class='post-desc'>
                <p>
                    <!-- this content will change using php -->
                    All these puppies must be hungry! I hope they got a good deal on all that food! Can you say $$$!
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
      <div class='right-side-items'>

      </div>
  </div>
</body>

</html>
