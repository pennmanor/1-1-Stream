<?php
  $dir = "/home/benjamin/Development/1-1-Stream/html/videos";

  $files = array();

  if(is_dir($dir)) {
    if($dh = opendir($dir)) {
      while(($file = readdir($dh)) != false) {
        if($file != '.' && $file != '..') {
          $files[] = $file;
        }
      }
    }
  }

  echo json_encode($files);
?>
