<?php
  if(!isset($_POST['filename']) || !isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['id'])) {
    http_response_code(400);
    echo '{"message": "Unable to update the episode with the information provided.", "info": "Invalid Parameters - filename: '.$filename.', title: '.$title.', description: '.$description.'","success": false}';
    return;
  }

  require('mysql-connect.php');
  $id = $_POST['id'];
  $filename = $_POST['filename'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $dir = VIDEO_DIR;
  $files = array();

  if(is_dir($dir)) {
    if($dh = opendir($dir)) {
      while(($file = readdir($dh)) != false) {
        if($file != '.' && $file != '..') {
          $files[] = $file;
        }
      }
    }
  }

  if(!in_array($filename, $files)) {
    http_response_code(400);
    echo '{"message": "The file was not found.", "info": "The file \''.$filename.'\' does not exist.","success": false}';
    return;
  }

  openConnection();

  $safeFilename = mysqli_escape_string($connection, $filename);
  $safeTitle = mysqli_escape_string($connection, $title);
  $safeDescription = mysqli_escape_string($connection, $description);
  $query = "UPDATE `episode` SET `filename`='$safeFilename', `title`='$safeTitle', `description`='$safeDescription' WHERE `id`='$id';";
  if(runQuery($query)) {
    echo "{\"id\": $id, \"filename\": \"$safeFilename\", \"title\": \"$safeTitle\", \"description\": \"$safeDescription\"}";
  }
  else {
    http_response_code(400);
    echo '{"message":"There was a problem creating the episode.", "info": "mysql error -'.getError().'", "query": "'.$query.'","success": false}';
  }
  closeConnection();
?>
