<!DOCTYPE html>
<?php
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh=ConnectDB();
  $post_id = $_GET['post_id'];
  $isAdmin = 0;
  $isAdmin = IsAdminUser($dbh, $_COOKIE['user_id']);
  $op = 'false';
  $postinfo=GetPost($post_id, $dbh);
  $signedInUser = GetUserInfo($_COOKIE['user_id'],$dbh);
  //print_r($postinfo);
  if($postinfo==0 ){
    header( 'Location: http://elvis.rowan.edu/~blackc6/awp/FaceBark/page-not-found.php');
    die();
  }

  if($signedInUser[0]->username == $postinfo[0]->username){
    $op = 'true';
  }
 ?>

<html lang='en'>
<head>
    <meta charset='UTF-8' />
    <title>Facebark</title>
    <link href='css/header.css' rel='stylesheet' />
    <link href='css/post.css' rel='stylesheet' />
    <link href='images/doggo1.jpg' rel='icon' />
    <style>
      .comment-form{
        display:flex;
        flex-direction: column;
        align-items: center;
      }
      #comment-submit {
       border-radius: 2px;
       box-shadow:  0px 2px 11px 0px rgba(0, 0, 0, 0.3);
       border: 1px solid #e2e6e6;
       margin: 10px 0 10px 0;
       font-family: 'Open Sans', sans-serif;
       outline: none;
       width: 395px;
       height: 100px;
      }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>

    var user = <?php echo json_encode(GetUserInfo($_COOKIE['user_id'],$dbh)); ?>;
    var comment_id;
    var post_id = <?php echo $post_id?>;
    var new_comment_count = 0;
    function myfunct(){
      var comment = $.trim($('#comment-submit').val());

      //to sanitize input
      //https://css-tricks.com/snippets/javascript/strip-html-tags-in-javascript/
      comment = comment.replace(/(<([^>]+)>)/ig,"");
      comment = hrefComment();
      //perform php function which should return id
      if (comment == ''){
        alert('Empty comments cannot be submitted.');
        $('#comment-submit').val('');
      }
      else {
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

    function hrefComment(comment){
      commentSplit = comment.split(" ");
      for(var i in commentSplit){
        if(i.indexOf("@") == 0){
          
        }
      }
      return comment;
    }
    </script>
</head>

<body>
    <?php include 'header.php'; ?>
    <?php
    $comments = GetAllComments($dbh, $post_id);
         ?>
    <div class='content'>

        <div class='post'>
            <div class='post-left'>
                <div class='post-header'>
                    <h2 class='post-title'>
              <!-- this content will change using php -->
              <?php echo ($postinfo[0]->post_title);?>
              <?php  if($signedInUser[0]->username == $postinfo[0]->username || $isAdmin === 1): ?>
                <a href="delete-post.php?post_id=<?php echo $post_id?>"><button>Delete post</button></a>
              <?php endif ?>
           </h2>
                    <h4 class='post-poster'>
              <!-- this content will change using php -->
              by <a href='./user.php?username=<?php echo ($postinfo[0]->username) ?>'><?php echo ($postinfo[0]->username) ?> </a> on <?php echo $postinfo[0]->post_timestamp?>
           </h4>
                </div>
                <div class='post-image'>
                    <!-- this content will change using php -->
                    <a href='post.php?post_id=<?php echo ($postinfo[0]->post_id) ?>'><img src='<?php echo ($postinfo[0]->file_path) ?>'></img></a>
                </div>
            </div>
            <div class='post-right'>
                <div class='post-desc'>
                    <p>
                        <!-- this content will change using php -->
                        <?php echo ($postinfo[0]->post_text) ?>
                    </p>
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
              <h4>
                Popularity: <?php echo $postinfo[0]->post_votes;?>
              </h4>
          </div>
            </div>
        </div>

        <div class='comments-container'>
          <div class='comment-form'>
              <textarea id='comment-submit' type='text' name='new_comment' form='commentform' placeholder='Write a comment (max 255 chars)' maxlength='255'></textarea><br/>

              <button type='button' onclick="myfunct()">Submit comment!</button>
          </div>
            <div class='first-comments-list comments-list '>
              <?php foreach( $comments as &$comment): ?>
                <div class='comment'>
                    <div class='comment-info'>
                        <div class='comment-tagline'>
                            <div class='comment-poster'>
                                <!-- this content will change using php -->
                                <a href='user.php?username=<?php echo $comment->username;?>'><?php echo $comment->username?></a>
                            </div>
                            <div class='comment-time'>
                                <!-- this content will change using php -->
                                <?php echo $comment->comment_timestamp;
                                if($signedInUser[0]->username == $comment->username  || $isAdmin == 1): ?>
                                <a href='delete-comment.php?comment_id=<?php echo $comment->comment_id?>&post_id=<?php echo $post_id?>'><button>Delete comment</button></a>
                              <?php endif ?>
                            </div>
                        </div>
                        <div class='comment-body'>
                            <!-- this content will change using php -->
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
