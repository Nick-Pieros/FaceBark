<!DOCTYPE html>
<?php
/*
*   reset_pass.php -- php that displays the password and confirmation password
*   after clicking the link to reset your password
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/

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
    // in the event someone types # or @ trying to search for them, they are removed
   if (strpos($searchTerm, '#') === 0) {
       $searchTerm = substr($searchTerm, 1);
   }
   if (strpos($searchTerm, '@') === 0) {
    $searchTerm = substr($searchTerm, 1);
   }

   //get the posts by title or hashtag, or the list of users that match the search criteria
  if($method == 'Titles'){
    $posts = SearchPostsByTitle($dbh, $page, $searchTerm);
    $postsNext = SearchPostsByTitle($dbh, $page+1, $searchTerm);
  } else if ($method == 'Tags') {
    $posts = SearchPostsByHashtags($dbh, $page, $searchTerm);
    $postsNext = SearchPostsByHashtags($dbh, $page+1, $searchTerm);
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
     <link href='css/search.css' rel='stylesheet' />

     <link href='images/doggo1.jpg' rel='icon' />

 </head>

 <body>
     <?php include 'header.php'; ?>
     <div class='content'>
       <!-- if searching by user, display all users where your search term is a substring
       of their username, if no string is entered, return all users -->
      <?php if($method == 'User'):?>
        <?php
        if($users ==0){
        echo "No users matching given criteria were found.";
      }
      ?>
      <div class='users-table'>
        <table class='user-table'>
          <?php
          for($i = 0; $i < count($users); $i = $i+3){
            echo '<tr>'.
            '<td><a href="./user.php?username='.$users[$i]->username.'">'.$users[$i]->username.'</a></td>'.
            '<td><a href="./user.php?username='.$users[$i+1]->username.'">'.$users[$i+1]->username.'</a></td>'.
            '<td><a href="./user.php?username='.$users[$i+2]->username.'">'.$users[$i+2]->username.'</a></td>'.
            '</tr>';
          }
        ?>
        </table>
      </div>
    <?php endif; ?>
       <?php if($method != 'User'):?>
         <!-- if searching by tags or title, display all posts where your search term
         of their matches, if no string is entered, return all posts -->
         <div class='left-side-items page-wrap' >
           <?php if($page > 1) :?>
             <a class='sidebar' href="<?php echo './search.php?searchTerm='.$searchTerm. '&method='.$method.'&page='. ($page-1) ?>">Prev </a>
           <?php endif; ?>
         </div>
         <div class='post-feed'>
           <div class='results'>
             <?php if($posts==0 && $method != 'User'){
                echo "No posts matching given criteria were found.";
              }?>
           </div>
             <?php
             // for loop to display all posts starts here
             foreach($posts as &$post):?>
         <div class='post '>
           <div class='post-left '>
             <div class='post-header '>
                 <h2 class='post-title '>
                   <?php echo ($post->post_title) ?>
                </h2>
                 <h4 class='post-poster '>
                   by <a href='./user.php?username=<?php echo ($post->username) ?>'><?php echo ($post->username) ?> </a> on <?php echo $post->post_timestamp?>
                </h4>
             </div>

             <div class='post-image'>
               <a href='post.php?post_id=<?php echo ($post->post_id) ?>'>  <img src='<?php echo ($post->file_path) ?>'></img></a>
             </div>
           </div>
     <div class='post-right'>
         <div class='post-desc'>
             <p> <?php echo ($post->post_text) ?></p>
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
         <h4>Popularity <?php echo $post->post_votes;?></h4>
       </div>
     </div>
     </div>
   <?php endforeach;?> <!-- end of the for loop for displaying posts-->
     </div>
     <div class='right-side-items page-wrap'>
       <?php if($postsNext != 0) :?> <!-- if there are more posts, continue-->
         <a class='sidebar' href="<?php echo './search.php?searchTerm='.$searchTerm. '&method='.$method.'&page='. ($page+1) ?>">Next </a>
       <?php endif; ?>
     </div>
   <?php endif;?><!-- end of the "if != users" statment-->
     </div>
 </body>

 </html>
