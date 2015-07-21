<?php
  if(!isset($_POST['name']) || !isset($_POST['episodeID'])) {
    http_response_code(400);
    echo '{"message": "Unable add a tag with the information provided", "info": "Parameters not set, needs `name` (STRING), `episodeID` (INT) ","success": false}';
    return;
  }

  require dirname(__FILE__).'/mysql-connect.php';

  $name = $_POST['name'];
  $episodeID = $_POST['episodeID'];

  openConnection();

  $safeName = mysqli_escape_string($connection, $name);
  $safeEpisodeID = mysqli_escape_string($connection, $episodeID);

  $result = getByValueFrom('id', $safeEpisodeID, 'episode');
  if(mysqli_num_rows($result) == 0) {
    http_response_code(400);
    echo '{"message": "Unable to add a tag to the episode.", "info": "Eposide `'.$safeEpisodeID.'` does not exist.", "success": false}';
    return;
  }

  $result2 = getByValueFrom('name', $safeName, 'tag');
  if(mysqli_num_rows($result2) == 0) {
    $query = "INSERT INTO `tag` (`name`) VALUES ('$safeName');";
    if(!runQuery($query)) {
      http_response_code(400);
      echo '{"message": "A server error has occured", "info": "mysql error -'.getError().'", "success": false}';
      return;
    }

    $tagID = mysqli_insert_id($connection);
  }
  else {
    $tag = mysqli_fetch_assoc($result2);
    $tagID = $tag['id'];
  }

  $query2 = "SELECT * FROM `tagLink` WHERE `tagID` = '$tagID' AND `episodeID` = '$safeEpisodeID';";

  $result3 = runQuery($query2);

  if(mysqli_num_rows($result3) == 0) {
    $query3 = "INSERT INTO `tagLink` (`tagID`, `episodeID`) VALUES ('$tagID', '$safeEpisodeID');";
    if(runQuery($query3)) {
      echo "{\"tag\": {\"id\": \"$tagID\", \"name\": \"$safeName\"}, \"tagLink\": {\"tagID\": \"$tagID\", \"episodeID\": \"$episodeID\"}}";
    }
    else {
      http_response_code(400);
      echo '{"message":"A server error has occured.", "info": "mysql error -'.getError().'", "query": "'.$query.'","success": false}';
    }
  }
  else {
    echo "{\"tag\": {\"id\": \"$tagID\", \"name\": \"$safeName\"}, \"tagLink\": {\"tagID\": \"$tagID\", \"episodeID\": \"$episodeID\"}}";
  }

  closeConnection();
?>
