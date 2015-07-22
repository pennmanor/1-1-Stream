<?php
  if(!isset($_POST['tagID']) || !isset($_POST['episodeID'])) {
    http_response_code(400);
    echo '{"message": "Unable add a tag with the information provided", "info": "Parameters not set, needs `tagID` (INT), `episodeID` (INT) ","success": false}';
    return;
  }

  require dirname(__FILE__).'/mysql-connect.php';

  $tagID = $_POST['tagID'];
  $episodeID = $_POST['episodeID'];

  openConnection();

  $safeTagID = mysqli_escape_string($connection, $tagID);
  $safeEpisodeID = mysqli_escape_string($connection, $episodeID);

  if(!runQuery("DELETE FROM `tagLink` WHERE `tagID` = '$safeTagID' AND `episodeID` = '$safeEpisodeID';")) {
    http_response_code(400);
    echo '{"message": "A server error has occured", "info": "mysql error -'.getError().'", "success": false}';
    return;
  }

  if(mysqli_affected_rows($connection) == 0) {
    echo '{"message": "Unable to remove the tag from the episode.", "info": "There is no link between Eposide `'.$safeEpisodeID.'` and tag `'.$safeTagID.'`.", "success": false}';
  }
  else {
    echo "{\"tag\": $safeTagID, \"episode\": $safeEpisodeID}";
  }

  closeConnection();
?>
