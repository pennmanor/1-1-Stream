
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
  $query = "DELETE t, tl FROM tag t LEFT JOIN tagLink tl ON t.id = tl.tagID WHERE t.id = $safeID;";
  if(runQuery($query)) {
    echo "{\"id\": $id}";
  }
  else {
    http_response_code(400);
    echo '{"message":"There was a problem deleting the episode.", "info": "mysql error -'.getError().'", "query": "'.$query.'","success": false}';
  }
  closeConnection();
?>
