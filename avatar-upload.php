<?php
   /*
   *   avatar-upload.php -- prompts user for image file to upload to change the
   *   avatar on the user's profile
   *
   *   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
   *   source: https://www.w3schools.com/howto/howto_css_modals.asp
   */
   ?>
<link href='css/modal.css' rel='stylesheet' />
<!-- Trigger/Open The Modal -->
<button id="myBtn">Change avatar!</button>
<!-- The Modal -->
<div id="myModal" class="modal">
   <!-- Modal content -->
   <div class="modal-content">
      <span class="close">&times;</span>
      <form action="avatar-update.php" method="post" enctype="multipart/form-data">
         Your Photo: <input type="file" name="upfile" size="25" />
         <input type="submit" name="submit" value="Update avatar!" />
         <input type="hidden" value="<?php echo ($userinfo[0]->user_id)?>" name="profile_id"/>
         <input type="hidden" value="<?php echo ($userinfo[0]->username)?>" name="username"/>
      </form>
   </div>
</div>
<script>
   var modal = document.getElementById('myModal');
   var btn = document.getElementById("myBtn");
   var span = document.getElementsByClassName("close")[0];
   btn.onclick = function() {
       modal.style.display = "block";
   }
   span.onclick = function() {
       modal.style.display = "none";
   }
   window.onclick = function(event) {
       if (event.target == modal) {
           modal.style.display = "none";
       }
   }
</script>
