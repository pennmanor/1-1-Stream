<?php
  $id = $_POST['id'];
  $filename = $_POST['filename'];
  $title = $_POST['title'];
  $description = $_POST['description'];

  $valid = !empty($filename) && !empty($title) && !empty($description) && !empty($id);

  if(!$valid) {
    http_response_code(400);
    echo '{"message": "Invalid Parameters - filename: '.$filename.', title: '.$title.', description: '.$description.', id: '.$id.',"success": false}';
    return;
  }

  require('mysql-connect.php');
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
    echo '{"message": "'.getError().'", "query": "'.$query.'","success": false}';
  }
  closeConnection();
?>
