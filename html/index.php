<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="1:1 Stream">
    <meta name="author" content="Benjamin Thomas">
    <title>1:1 Podcast Stream</title>
    <link rel="stylesheet" href="styles/bootstrap.min.css">
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
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">1:1 Stream</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Live<span class="sr-only">(current)</span></a></li>
            <li><a href="episodes.html">Episodes</a></li>
            <li><a href="manageEpisodes.php">Manage</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Exit</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
      <h1 class="page-header text-center">1:1 Podcast Stream</h1>
      <div class="row">
        <div class="col-md-offset-2 col-md-8">
          <video id="videoStream" class="video-js vjs-default-skin"
           controls
           preload="auto"
           autoplay
           poster="images/1to1-logo.png"
           width="auto" height="auto">
           <source src="rtmp://<?php echo $_SERVER['SERVER_ADDR']; ?>/autoplay/autoplay" type="rtmp/mp4">
           <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
          </video>
        </div>
      </div>
    </div>
    <!-- Stream Offline-->
    <div class="modal fade" id="offlineModal" tabindex="-1" role="dialog" aria-labelledby="offlineModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="offlineModalLabel">Uh-oh!</h4>
          </div>
          <div class="modal-body">
            It looks like this stream is offline. Would you like to view previous episodes?
          </div>
          <div class="modal-footer">
            <a href="episodes.html"><button type="button" class="btn btn-success">Yes</button></a>
            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/video.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    var videoPlayer = videojs('videoStream');
    var err;
    videoPlayer.on('error', function(event){
      if(event.target.outerText != null || event.target.outerText == "↵FLASH: rtmpconnectfailure"){
      //  window.location = "episodes.html";
      $('#offlineModal').modal('show');
      }
    });
  });
  videojs.options.flash.swf = "video-js.swf";
  </script>
</html>
