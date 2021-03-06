<?php
  $id = $_POST['id'];

  $valid = !empty($id);

  if(!$valid) {
    http_response_code(400);
    echo '{"message": "Unable to delete the episode with the information provided.", "info": "Invalid Parameters - id: '.$_POST['id'].',"success": false}';
    return;
  }

  require dirname(__FILE__).'/mysql-connect.php';
  openConnection();

  $safeID = mysqli_escape_string($connection, $id);
  $query = "DELETE e, tl FROM episode e LEFT JOIN tagLink tl ON e.id = tl.episodeID WHERE e.id = $safeID;";
  if(runQuery($query)) {
    echo "{\"id\": $id}";
  }
  else {
    http_response_code(400);
    echo '{"message":"There was a problem deleting the episode.", "info": "mysql error -'.getError().'", "query": "'.$query.'","success": false}';
  }
  closeConnection();
?>
