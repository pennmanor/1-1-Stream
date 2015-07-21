<?php
  require dirname(__FILE__).'/php/mysql-connect.php';
  openConnection();
  $tagsID = array();
  $tags = array();
  $id = $_GET['id'];
  $result = getByValueFrom('id', $id, 'episode');
  $result2 = getByValueFrom('episodeID', $id, 'tagLink');

  while($tagLink = mysqli_fetch_assoc($result2)) {
    $tagsID[] = $tagLink['tagID'];
  }

  if(count($tagsID) > 0) {
    $tagCount = 0;
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

    while($tag = mysqli_fetch_assoc($result3)) {
      $tag['id'] = intval($tag['id']);
      $tags[$tag['id']] = $tag;
    }
  }

  closeConnection();
  if(!$episode = mysqli_fetch_assoc($result)) {
    http_response_code(404);
    echo "404";
    return;
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="1:1 Stream">
    <meta name="author" content="Benjamin Thomas">
    <title>1:1 Podcast Episode: <?php echo $episode['title']; ?></title>
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="styles/bootstrap-theme.min.css">
    <link rel="stylesheet" href="styles/angular-toastr.min.css">
    <link rel="stylesheet" href="styles/video-js.css">
    <style type="text/css">
      .vjs-default-skin { color: #ffffff; }
      .vjs-default-skin .vjs-control-bar { font-size: 103% }
      .video-js {padding-top: 56.25%}
      .vjs-fullscreen {padding-top: 0px}
    </style>
  </head>
  <body>
    <div class="container">
      <h1 class="page-header"><?php echo $episode['title']?></h1>
      <div class="row">
        <div class="col-md-7">
          <video class="video-js vjs-default-skin" controls
           preload="auto"
           poster="images/pm-logo.jpg"
           width="auto" height="auto"
           data-setup="{}">
           <source src="videos/<?php echo $episode['filename'] ?>" type="video/mp4">
           <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
          </video>
        </div>
        <div class="col-md-5">
          <p class="lead"><?php echo $episode['description']?></p>
          <p class="lead">
            <?php
            foreach ($tags as $tag) {
              echo '<span class="label label-info">'.$tag['name'].'</span>&nbsp;';
            }
            ?>
          </p>
        </div>
      </div>
      <hr>
      <div class="text-center lead">
        <a href="episodes.html">View All Episodes</a>
      </div>
    </div>
  </body>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/video.js"></script>
  <script type="text/javascript">
      videojs.options.flash.swf = "video-js.swf"
  </script>
</html>
