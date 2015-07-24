<?php
  require dirname(__FILE__).'/mysql-connect.php';
  openConnection();

  if(!isset($_GET['q'])) {
    echo '[]';
    return;
  }
  $query = 'SELECT t.id, t.name, e.title as episodeTitle, e.id as episodeID FROM tag t JOIN tagLink tl on t.id = tl.tagID JOIN episode e on e.id = tl.episodeID;';
  $result = runQuery($query);

  closeConnection();
  $tags = array();

  while($row = mysqli_fetch_assoc($result)) {
    if(isset($row['episodeTitle']) && $row['episodeID']) {
      $episode = array(
        'id' => intval($row['episodeID']),
        'title' => $row['episodeTitle']
      );
    }
    unset($row['episodeTitle']);
    unset($row['episodeID']);
    $row['id'] = intval($row['id']);
    if(!isset($tags[$row['id']])) {
      $row['episodes'] = array();
      $tags[$row['id']] = $row;
    }

    if(isset($episode)) {
      $tags[$row['id']]['episodes'][] = $episode;
    }
    unset($episode);
  }

  echo json_encode(array_values($tags));
 ?>
