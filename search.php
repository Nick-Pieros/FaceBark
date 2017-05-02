<!DOCTYPE html>
<?php
  // search.php is essentially a filtered gallery
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh = ConnectDB();
  $searchTerm = $_GET['searchTerm'];
  $method = $_GET['method'];
  if (isset($_GET['page'])){
    $page = $_GET['page'];
  } else {
    $page = 1;
  }
  if($method == 'Titles'){
    $posts = SearchPostsByTitle($dbh, $page, $searchTerm);
  } else if ($method == 'Tags') {
    $posts = SearchPostsByHashtags($dbh, $page, $searchTerm);
  } else if ($method == 'User') {
    $users = SearchUsers($dbh, $searchTerm);
  } else {
    $posts = 0;
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

     <link href='images/doggo1.jpg' rel='icon' />
 </head>

 <body>
     <?php include 'header.php'; ?>
     <div class='content'>
         <div class='post-feed'>
             <?php
             if($posts==0 && $method != 'User'){
               echo "No posts matching given criteria were found.";
             } else if ($users == 0 && $method == 'User'){
               echo "No users matching given criteria were found.";
             }
             foreach($users as $user){
               echo '<a href="./user.php?username='. $user->username .'">'.$user->username .'</a><br/>';
             }
             foreach($posts as &$post):?>
         <div class='post '>
           <div class='post-left '>
             <div class='post-header '>
                 <h2 class='post-title '>
                   <!-- this content will change using php -->
                   <?php echo ($post->post_title) ?>
                </h2>
                 <h4 class='post-poster '>
                   <!-- this content will change using php -->
                   by <a href='./user.php?username=<?php echo ($post->username) ?>'><?php echo ($post->username) ?> </a> on <?php echo $post->post_timestamp?>
                </h4>
             </div>
             <?php if($post->file_path):?>
             <div class='post-image'>
                 <!-- this content will change using php -->
               <a href='post.php?post_id=<?php echo ($post->post_id) ?>'>  <img src='<?php echo ($post->file_path) ?>'></img></a>

             </div>
           <?php else: ?>
             <a href='post.php?post_id=<?php echo ($post->post_id) ?>'>
             <div class='post-image'>
                 <!-- this content will change using php -->
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
                 <img src='images/like-paw.png'></img>
             </div>
             <div class='post-downvote'>
                 <img src='images/dislike-paw.png'></img>
             </div>
         </div>
     </div>
     </div>
     <?php endforeach;?>
     </div>
     <div class='right-side-items'>
       <?php if($page > 1) :?>
         <a href="<?php echo './search.php?searchTerm='.$searchTerm. '&method='.$method.'&page='. ($page-1) ?>">Prev </a>
       <?php endif; ?>
         <br/>
       <?php if($posts != 0) :?>
         <a href="<?php echo './search.php?searchTerm='.$searchTerm. '&method='.$method.'&page='. ($page+1) ?>">Next </a>
       <?php endif; ?>
     </div>
     </div>
 </body>

 </html>
