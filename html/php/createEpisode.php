<?php

  if(!isset($_POST['filename']) || !isset($_POST['title']) || !isset($_POST['description'])) {
    http_response_code(400);
    echo '{"message": "Unable to create an episode with the information provided.", "info": "Invalid Parameters - filename: '.$_POST['filename'].', title: '.$_POST['title'].', description: '.$_POST['description'].'","success": false}';
    return;
  }

  require dirname(__FILE__).'/mysql-connect.php';
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
  $query = "INSERT INTO `episode` (`filename`, `title`, `description`) VALUES ('$safeFilename', '$safeTitle', '$safeDescription');";
  if(runQuery($query)) {
    $episodeID = mysqli_insert_id($connection);
    echo "{\"id\": $episodeID, \"filename\": \"$safeFilename\", \"title\": \"$safeTitle\", \"description\": \"$safeDescription\"}";
  }
  else {
    http_response_code(400);
    echo '{"message":"There was a problem creating the episode.", "info": "mysql error -'.getError().'", "query": "'.$query.'","success": false}';
  }
  closeConnection();
?>
