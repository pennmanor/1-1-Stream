<?php
  require dirname(__FILE__).'/mysql-connect.php';
  openConnection();

  $query = 'SELECT e.id, e.filename, e.title, e.description, e.viewable, t.name as tagName, t.id as tagID FROM (episode e LEFT JOIN tagLink tl ON e.id = tl.episodeID) LEFT JOIN tag t on t.id = tl.tagID ORDER BY e.id';
  $result = runQuery($query);


  closeConnection();
  $episodes = array();

  while($row = mysqli_fetch_assoc($result)) {
    if(isset($row['tagID']) && $row['tagName']) {
      $tag = array(
        'id' => intval($row['tagID']),
        'name' => $row['tagName']
      );
    }
    unset($row['tagName']);
    unset($row['tagID']);
    $row['id'] = intval($row['id']);
    if(!isset($episodes[$row['id']])) {
      $row['tags'] = array();
      $episodes[$row['id']] = $row;
    }

    if(isset($tag)) {
      $episodes[$row['id']]['tags'][] = $tag;
    }
    unset($tag);
  }

  echo json_encode(array_values($episodes));
 ?>
