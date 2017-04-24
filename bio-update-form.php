
<!-- borrowed from: https://www.w3schools.com/howto/howto_css_modals.asp -->
<link href='css/modal.css' rel='stylesheet' ></link>
<script src="js/modal.js"></script>
<!-- Trigger/Open The Modal -->
<button id="myBtn">Change doggo bio!</button>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <form name='bio-form' action='update-bio.php' method='post' >
       <input id='name' type='text' name='name' value="<?php echo ($doggoinfo[0]->dog_name)?>"> <br/>
       <input id='breed' type='text' name='breed'value="<?php echo ($doggoinfo[0]->dog_breed)?>"><br/>
       <input id='weight' type='text' name='weight' value="<?php echo ($doggoinfo[0]->dog_weight)?>"><br/>
       <input id='bio' type='text' name='bio' value="<?php echo ($doggoinfo[0]->dog_bio)?>"> <br/>
       <input type='submit' value='Update bio!'>
    </form>
  </div>

</div>
