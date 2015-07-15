<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="styles/video-js.css" rel="stylesheet">
    <script src="js/video.js"></script>
    <script>
        videojs.options.flash.swf = "video-js.swf"
    </script>
    <style type="text/css">
      .vjs-default-skin { color: #ffffff; }
      .vjs-default-skin .vjs-control-bar { font-size: 103% }
    </style>
</head>
<body>
    <div class="main">
        <div id="content_area" class="well">
            <div class="vid_player">
                <video id="MY_VIDEO_1" class="video-js vjs-default-skin" controls
                 preload="auto" width="960" height="540"
                 poster="images/pm-logo.jpg"
                 data-setup="{}">
                 <source src="videos/<?php echo $_GET['name'] ?>" type="video/mp4">
                 <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                </video>
            </div>
        </div>
    </div>
</body>
</html>
