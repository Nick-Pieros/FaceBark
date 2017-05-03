
<?php
/*
*   upload2.php -- does the actualy moving and uploading of files
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*   source: http://php.net/manual/en/features.file-upload.php
*/
  // prepare the profile information
  require_once ("functions.php");
  require_once ("connect.php");
  $dbh = ConnectDB();
  $user_id = $_COOKIE['user_id'];
  $dirname = "uploads/";


  //header('Content-Type: text/plain; charset=utf-8');

  try {

      // Undefined | Multiple Files | $_FILES Corruption Attack
      // If this request falls under any of them, treat it invalid.
      if (
          !isset($_FILES['upfile']['error']) ||
          is_array($_FILES['upfile']['error'])
      ) {
          throw new RuntimeException('Invalid parameters.');
      }

      // Check $_FILES['upfile']['error'] value.
      switch ($_FILES['upfile']['error']) {
          case UPLOAD_ERR_OK:
              break;
          case UPLOAD_ERR_NO_FILE:
              throw new RuntimeException('No file sent.');
          case UPLOAD_ERR_INI_SIZE:
          case UPLOAD_ERR_FORM_SIZE:
              throw new RuntimeException('Exceeded filesize limit.');
          default:
              throw new RuntimeException('Unknown errors.');
      }

      // You should also check filesize here.
      if ($_FILES['upfile']['size'] > 1000000) {
          throw new RuntimeException('Exceeded filesize limit.');
      }

      // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
      // Check MIME Type by yourself.
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      if (false === $ext = array_search(
          $finfo->file($_FILES['upfile']['tmp_name']),
          array(
              'jpg' => 'image/jpeg',
              'png' => 'image/png',
              'gif' => 'image/gif',
          ),
          true
      )) {
          throw new RuntimeException('Invalid file format.');
      }

      // You should name it uniquely.
      // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
      // On this example, obtain safe unique name from its binary data.
      $filename = uniqid(($user_id . "_"));
      if (!move_uploaded_file(
          $_FILES['upfile']['tmp_name'],
          sprintf('./uploads/%s.%s',
              $filename,
              $ext
          )
      )) {
          throw new RuntimeException('Failed to move uploaded file.');
      }

      $filepath = $dirname.$filename.".".$ext;
      NewUpload($dbh, $filename, $filepath, ".".$ext, $user_id);

  } catch (RuntimeException $e) {

      echo $e->getMessage();

  }

  ?>
