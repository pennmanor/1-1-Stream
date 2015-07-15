<?php
  require('mysql-connect.php');

  $filename = $_POST['filename'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $dir = VIDEO_DIR;
  $files = array();

  $valid = !empty($filename) && !empty($title) && !empty($description);

  if(!$valid) {
    http_response_code(400);
    echo '{"message": "Invalid Parameters - filename: '.$filename.', title: '.$title.', description: '.$description.'","success": false}';
    return;
  }

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
    echo '{"message": "The file \''.$filename.'\'" does not exist.","success": false}';
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
    echo '{"message": "'.getError().'", "query": "'.$query.'","success": false}';
  }
  closeConnection();
?>
