<?php
  require dirname(__FILE__).'/mysql-connect.php';

  if(isset($_GET['filter'])) {
    $filter = $_GET['filter'];
  }
  else {
    $filter = '';
  }

  switch($filter) {
    case 'IN_DB':
      echo json_encode(getDBFiles());
    break;
    case 'NOT_IN_DB':
      $dirFiles = getDIRFiles();
      $dbFiles = getDBFiles();
      $filteredFiles = array_values(array_diff($dirFiles, $dbFiles));
      echo json_encode($filteredFiles);
    break;
    default:
      echo json_encode(getDIRFiles());
  }

  function getDIRFiles() {
    $dirFiles = array();

    $dir = VIDEO_DIR;
    $files = array();

    if(is_dir($dir)) {
      if($dh = opendir($dir)) {
        while(($file = readdir($dh)) != false) {
          if($file != '.' && $file != '..' && $file != '.gitkeep') {
            $dirFiles[] = $file;
          }
        }
      }
    }

    return $dirFiles;
  }

  function getDBFiles() {
    $dbFiles = array();

    openConnection();
    $result = getAllFrom("episode");
    closeConnection();

    while($episode = mysqli_fetch_assoc($result)) {
      $dbFiles[] = $episode['filename'];
    }

    return $dbFiles;
  }
?>
