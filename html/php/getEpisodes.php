<?php
  require dirname(__FILE__).'/mysql-connect.php';
  openConnection();
  $result = getAllFrom("episode");
  closeConnection();
  $episodes = array();

  while($episode = mysqli_fetch_assoc($result)) {
    $episode['id'] = intval($episode['id']);
    $episode['tags'] = array();
    $episodes[$episode['id']] = $episode;
  }

  if(count($episodes) > 0) {
    $query = 'SELECT `tagID`, `episodeID` FROM `tagLink` WHERE ';
    $episodeCount = 0;

    foreach ($episodes as $index => $episode) {
      $episodeCount++;
      $query .= '`episodeID` = \''.$episode['id'].'\'';
      if(count($episodes) == $episodeCount) {
        $query .= ';';
      }
      else {
        $query .= ' OR ';
      }
    }

    $result2 = runQuery($query);
    $tagsID = array();
    $tagLinks = array();

    while($tagLink = mysqli_fetch_assoc($result2)) {
      $tagLink['episodeID'] = intval($tagLink['episodeID']);
      $tagLink['tagID'] = intval($tagLink['tagID']);
      $tagsID[] = $tagLink['tagID'];
      $tagLinks[] = $tagLink;
    }

    $tagsID = array_unique($tagsID);
    $tagCount = 0;

    if(count($tagsID) > 0) {
      $query2 = 'SELECT * FROM `tag` WHERE ';
      foreach ($tagsID as $index => $tagID) {
        $tagCount++;
        $query2 .= '`id` = \''.$tagID.'\'';
        if(count($tagsID) == $tagCount) {
          $query2 .= ';';
        }
        else {
          $query2 .= ' OR ';
        }
      }

      $result3 = runQuery($query2);
      $tags = array();

      while($tag = mysqli_fetch_assoc($result3)) {
        $tag['id'] = intval($tag['id']);
        $tags[$tag['id']] = $tag;
      }

      foreach ($tagLinks as $index => $tagLink) {
        $episodes[$tagLink['episodeID']]['tags'][] = $tags[$tagLink['tagID']];
      }
    }
  }

  echo json_encode(array_values($episodes));
 ?>
