<?php
  require("mysql-connect.php");
  openConnection();
  $result = getAllFrom("episode");
  closeConnection();
  $episodes = array();
  while($episode = mysqli_fetch_assoc($result)) {
    $episodes[] = $episode;
  }
  echo json_encode($episodes);
 ?>
