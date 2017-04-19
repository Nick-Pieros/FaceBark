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
                  I must be hungry, but these puppies look like fried chicken.
               </h2>
                <h4 class='post-poster'>
                  <!-- this content will change using php -->
                  by <a href='./profile.html'>CutestPuppyEvR</a>
               </h4>
            </div>
            <div class='post-image'>
                <!-- this content will change using php -->
                <img src='images/friedChicken.png'></img>
            </div>
            <div class='post-desc'>
                <p>
                    <!-- this content will change using php -->
                    These puppies are adorable! They look just like fried chicken!                </p>
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
                                <a href=''>McCuddles1</a>
                            </div>
                            <div class='comment-time'>
                                <!-- this content will change using php -->
                                March 25, 2017
                            </div>
                        </div>
                        <div class='comment-body'>
                            <!-- this content will change using php -->
                            This picture makes me so hungry!
                        </div>
                    </div>
                    <div class='comments-list'>
                        <div class='comment'>
                            <div class='comment-info'>
                                <div class='comment-tagline'>
                                    <div class='comment-poster'>
                                        <!-- this content will change using php -->
                                        <a href=''>BONEappetite</a>
                                    </div>
                                    <div class='comment-time'>
                                        <!-- this content will change using php -->
                                        March 25, 2017
                                    </div>
                                </div>
                                <div class='comment-body'>
                                    <!-- this content will change using php -->
                                    Yes it does McCuddles1! Maybe you and I can be taken on a walk in the park tomorrow?
                                </div>
                            </div>
                        </div>
                        <div class='comment'>
                            <div class='comment-info'>
                                <div class='comment-tagline'>
                                    <div class='comment-poster'>
                                        <!-- this content will change using php -->
                                        <a href=''>McCuddles1</a>
                                    </div>
                                    <div class='comment-time'>
                                        <!-- this content will change using php -->
                                        March 25, 2017
                                    </div>
                                </div>
                                <div class='comment-body'>
                                    <!-- this content will change using php -->
                                    Yeah lets do that! Ill bark at my owner until he lets me!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='comment'>
                        <div class='comment-info'>
                            <div class='comment-tagline'>
                                <div class='comment-poster'>
                                    <!-- this content will change using php -->
                                    <a href=''>CaughtMyTail</a>
                                </div>
                                <div class='comment-time'>
                                    <!-- this content will change using php -->
                                    March 26, 2017
                                </div>
                            </div>
                            <div class='comment-body'>
                                <!-- this content will change using php -->
                                I hope these puppies don't fall down the crrate!
                            </div>
                        </div>
                        <div class='comments-list'>
                            <div class='comment'>
                                <div class='comment-info'>
                                    <div class='comment-tagline'>
                                        <div class='comment-poster'>
                                            <!-- this content will change using php -->
                                            <a href=''>CaughtMyTail</a>
                                        </div>
                                        <div class='comment-time'>
                                            <!-- this content will change using php -->
                                            March 26, 2017
                                        </div>
                                    </div>
                                    <div class='comment-body'>
                                        <!-- this content will change using php -->
                                        crate*
                                    </div>
                                </div>
                                <div class='comments-list'>
                                    <div class='comment'>
                                        <div class='comment-info'>
                                            <div class='comment-tagline'>
                                                <div class='comment-poster'>
                                                    <!-- this content will change using php -->
                                                    <a href=''>BarkingUpTheWrongTree</a>
                                                </div>
                                                <div class='comment-time'>
                                                    <!-- this content will change using php -->
                                                    March 26, 2017
                                                </div>
                                            </div>
                                            <div class='comment-body'>
                                                <!-- this content will change using php -->
                                                Lorem ipsum dolor sit amet, nonummy ligula volutpat hac integer nonummy. Suspendisse ultricies, congue etiam tellus, erat libero, nulla eleifend, mauris pellentesque. Suspendisse integer praesent vel, integer gravida mauris, fringilla vehicula lacinia non
                                            </div>
                                        </div>
                                    </div>
                                    <div class='comment'>
                                        <div class='comment-info'>
                                            <div class='comment-tagline'>
                                                <div class='comment-poster'>
                                                    <!-- this content will change using php -->
                                                    <a href=''>BarkingDownTheWrongTree</a>
                                                </div>
                                                <div class='comment-time'>
                                                    <!-- this content will change using php -->
                                                    March 26, 2017
                                                </div>
                                            </div>
                                            <div class='comment-body'>
                                                <!-- this content will change using php -->
                                                Nice Name!
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
