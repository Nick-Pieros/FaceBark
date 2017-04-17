<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8' />
    <title>Facebark</title>
    <link href='css/header.css' rel='stylesheet' />
    <link href='css/post.css' rel='stylesheet' />
    <link href='images/doggo1.jpg' rel='icon' />
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <div class='content'>
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
                <img src='images/puppiesFeeding.jpg'></img>
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
        <!--
           comment list contains comment trees
           comment trees contain parent and comment trees of it's children

           examples below: comments-list
                  /         \
              comment        comment
                          /          \
                     comment-info   comments-list
                                    /   |     \
                              comment comment comment
           -->
        <div class='comments-container'>
            <div class='first-comments-list comments-list '>
                <div class='comment'>
                    <div class='comment-info'>
                        <div class='comment-tagline'>
                            <div class='comment-poster'>
                                <!-- this content will change using php -->
                                <a href=''>101Dalmations</a>
                            </div>
                            <div class='comment-time'>
                                <!-- this content will change using php -->
                                March 25, 2017
                            </div>
                        </div>
                        <div class='comment-body'>
                            <!-- this content will change using php -->
                            Nice.
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
