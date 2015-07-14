<?php
  $dir = "/usr/local/nginx/html/videos/";

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
