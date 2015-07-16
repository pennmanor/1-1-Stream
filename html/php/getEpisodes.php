<?php
  require("mysql-connect.php");
  openConnection();
  $result = getAllFrom("episode");
  closeConnection();
  $episodes = array();
  while($episode = mysqli_fetch_assoc($result)) {
    $episode['id'] = intval($episode['id']);
    $episodes[] = $episode;
  }
  echo json_encode($episodes);
 ?>
