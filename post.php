<!DOCTYPE html>
<?php
/*
*   post.php -- single detailed page view of a post; consists of title, comments,
*   photo upload, captions, and popularity
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/


  require_once ("functions.php");
  require_once ("connect.php");
  $dbh=ConnectDB();

  $post_id = $_GET['post_id'];

  // a moderator is the original poster or an admin
  // moderators have the power to delete a
  // or remove a comment
  $isModerator = 0;
  $isModerator = IsAdminUser($dbh, $_COOKIE['user_id']);

  $postinfo = GetPost($post_id, $dbh);
  $signedInUser = GetUserInfo($_COOKIE['user_id'],$dbh);

  // if a post isn't found, redirect it to the 404 page
  if($postinfo==0 ){
    header( 'Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/page-not-found.php');
    die();
  }
  // if the signed in user is the same user who made the post_id
  // they are considered a moderator
  if($signedInUser[0]->username == $postinfo[0]->username){
    $isModerator = 1;
  }
 ?>

<html lang='en'>
<head>
    <meta charset='UTF-8' />
    <title>Facebark</title>
    <link href='css/header.css' rel='stylesheet' />
    <link href='css/post.css' rel='stylesheet' />
    <link href='images/doggo1.jpg' rel='icon' />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>

    var user = <?php echo json_encode(GetUserInfo($_COOKIE['user_id'],$dbh)); ?>;
    var comment_id;
    var post_id = <?php echo $post_id?>;
    var new_comment_count = 0;

    function submitComment(){
      var comment = $.trim($('#comment-submit').val());

      //to sanitize input
      //https://css-tricks.com/snippets/javascript/strip-html-tags-in-javascript/
      comment = comment.replace(/(<([^>]+)>)/ig,"");
      comment = hrefComment(comment);

      //perform php function which should return id
      if (comment == ''){
        alert('Empty comments cannot be submitted.');
        $('#comment-submit').val('');
      }
      else { //creates the comment that is to be displayed by redrawing
        $('.comments-list').append(
          "<div class='comment'>"+
             "<div class='comment-info'>" +
                  "<div class='comment-tagline'>"+
                      "<div class='comment-poster'>"+
                          "<a href='user.php?username="+user[0].username+"'>"+user[0].username+"</a>"+
                      "</div>"+
                      "<div class='comment-time new-comment-"+ new_comment_count+"'>"+
                          "Just now"+
                          "<a href='delete-comment.php?comment_id="+comment_id+"&post_id="+post_id+"'><button>Delete comment</button></a>"+
                      "</div>"+
                  "</div>"+
                  "<div class='comment-body'>"+
                      comment+
                  "</div>"+
              "</div>"+
          "</div>"
        );

        // ajax call to the comment submit function that creates the post
        $('#comment-submit').val('');
        $.ajax({
            url: 'create-comment.php',
            type: 'post',
            data: {'post_id': post_id, 'new_comment': comment},
            success: function(data, status) {
              $('.new-comment-' + new_comment_count).html("Just now <a href='delete-comment.php?comment_id="+data+"&post_id="+post_id+"'><button>Delete comment</button></a>");
            },
            error: function(xhr, desc, err) {
              console.log(xhr);
              console.log("Details: " + desc + "\nError:" + err);
            }
          });
      }

    };

    // creates the anchors for @mentions and #tags
    function hrefComment(comment){
      var commentSplit = comment.split(" ");
      var newComment = [];

      commentSplit.forEach(function(i){
        if(i.indexOf("@") == 0){
          i = "<a href='user.php?username=" + i.substring(1) + "'>" + i + "</a>";
        };
        if(i.indexOf("#") == 0){
          i = "<a href='search.php?method=Tags&searchTerm=" + i.substring(1) + "'>" + i + "</a>";
        };
        newComment.push(i);
      }
      );
      comment = newComment.join(" ");
      return comment;
    }

    // disallows newlines
    function noNewLines(t){
      if(t.value.match(/\n/)){
        t.value=t.value.replace(/\n/g,' ');
      }
    }


    </script>
</head>

<body>
    <?php include 'header.php'; ?>
    <?php
      // retrieve all comments
      $comments = GetAllComments($dbh, $post_id);
      ?>
    <div class='content'>
        <div class='post'>
            <div class='post-left'>
                <div class='post-header'>
                    <h2 class='post-title'>
                      <?php echo ($postinfo[0]->post_title);?>

                      <!-- if the you are a moderator you will see the buttons to allow the deleting of posts and comments  -->
                      <?php  if( $isModerator=== 1): ?>
                        <a href="delete-post.php?post_id=<?php echo $post_id?>"><button>Delete post</button></a>
                      <?php endif ?>
                    </h2>
                    <h4 class='post-poster'>
                      by <a href='./user.php?username=<?php echo ($postinfo[0]->username) ?>'><?php echo ($postinfo[0]->username) ?> </a> on <?php echo $postinfo[0]->post_timestamp?>
                    </h4>
                </div>
                <div class='post-image'>
                    <a href='post.php?post_id=<?php echo ($postinfo[0]->post_id) ?>'><img src='<?php echo ($postinfo[0]->file_path) ?>'></img></a>
                </div>
            </div>
            <div class='post-right'>
                <div class='post-desc'>
                    <p><?php echo ($postinfo[0]->post_text) ?></p>
                </div>
                  <div class='post-voting'>
                    <div class='post-upvote'>
                        <a href='vote.php?vote=1&post_id=<?php echo $post_id?>'><img src='images/like-paw.png'></img></a>
                    </div>
                    <div class='post-downvote'>
                        <a href='vote.php?vote=-1&post_id=<?php echo $post_id?>'><img src='images/dislike-paw.png'></img></a>
                    </div>
                  </div>
              <div>
              <h4>Popularity: <?php echo $postinfo[0]->post_votes;?></h4>
              </div>
            </div>
        </div>
        <!-- list of comments for this post -->
        <div class='comments-container'>
          <div class='comment-form'>
              <textarea id='comment-submit' type='text' name='new_comment' onkeyup="noNewLines(this)"  form='commentform' placeholder='Write a comment (max 255 chars)' maxlength='255'></textarea><br/>
              <button type='button' onclick="submitComment()">Submit comment!</button>
          </div>
            <div class='first-comments-list comments-list '>
              <?php foreach( $comments as &$comment): ?>
                <div class='comment'>
                    <div class='comment-info'>
                        <div class='comment-tagline'>
                            <div class='comment-poster'>
                                <a href='user.php?username=<?php echo $comment->username;?>'><?php echo $comment->username?></a>
                            </div>
                            <div class='comment-time'>
                                <?php echo $comment->comment_timestamp;
                                if($signedInUser[0]->username == $comment->username  || $isModerator== 1): ?>
                                <a href='delete-comment.php?comment_id=<?php echo $comment->comment_id?>&post_id=<?php echo $post_id?>'><button>Delete comment</button></a>
                              <?php endif ?>
                            </div>
                        </div>
                        <div class='comment-body'>
                            <?php echo $comment->comment_text ?>
                        </div>
                    </div>
                </div>
              <?php endforeach;?>
            </div>
        </div>
    </div>
</body>

</html>
