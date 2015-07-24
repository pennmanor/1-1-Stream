<?php
  require dirname(__FILE__).'/mysql-connect.php';
  openConnection();

  if(isset($_GET['q'])) {
    $result = getLikeValueFrom('name', $_GET['q'], 'tag');
  }
  else {
    $result = getAllFrom('tag');
  }

  closeConnection();
  $tags = array();

  while($row = mysqli_fetch_assoc($result)) {
    $row['id'] = intval($row['id']);
    $tags[] = $row;
  }

  echo json_encode($tags);
 ?>
