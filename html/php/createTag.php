<?php

  if(empty($_POST['name'])) {
    http_response_code(400);
    echo '{"message": "Unable to create a with the information provided.", "info": "Parameters were not passed","success": false}';
    return;
  }

  $name = $_POST['name'];

  require dirname(__FILE__).'/mysql-connect.php';

  openConnection();

  $safeName = mysqli_escape_string($connection, $name);

  $result = getByValueFrom('name', $safeName, 'tag');
  if(mysqli_num_rows($result) == 0) {
    $query = "INSERT INTO `tag` (`name`) VALUES ('$safeName');";
    if(!runQuery($query)) {
      http_response_code(400);
      echo '{"message": "A server error has occured", "info": "mysql error -'.getError().'", "success": false}';
      return;
    }

    $tagID = mysqli_insert_id($connection);
    echo "{\"id\": $tagID, \"name\": \"$safeName\"}";
  }
  else {
    http_response_code(400);
    echo '{"message":"A tag with that name exists.", "info": "Tag exists in database","success": false}';
  }
  closeConnection();
?>
