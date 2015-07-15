<?php
  $id = $_POST['id'];

  $valid = !empty($id);

  if(!$valid) {
    http_response_code(400);
    echo '{"message": "Invalid Parameters - id: '.$id.',"success": false}';
    return;
  }

  require('mysql-connect.php');
  openConnection();

  $query = "DELETE FROM `episode` WHERE `id`='$id';";
  if(runQuery($query)) {
    echo "{\"id\": $id}";
  }
  else {
    http_response_code(400);
    echo '{"message": "'.getError().'", "query": "'.$query.'","success": false}';
  }
  closeConnection();
?>
