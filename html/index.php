<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="1:1 Stream">
    <meta name="author" content="Benjamin Thomas">
    <title>1:1 Podcast Stream</title>
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
      <h1 class="page-header text-center">1:1 Podcast Stream</h1>
      <div class="row">
        <div class="col-md-offset-2 col-md-8">
          <video class="video-js vjs-default-skin" controls
           preload="auto"
           poster="images/pm-logo.jpg"
           width="auto" height="auto"
           data-setup="{}">
           <source src="rtmp://<?php echo $_SERVER['SERVER_ADDR']; ?>/autoplay/autoplay" type="rtmp/mp4">
           <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
          </video>
        </div>
      </div>
      <hr>
      <div class="text-center lead">
        <a href="episodes.html">View Past Episodes</a>
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
