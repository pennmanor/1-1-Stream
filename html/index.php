<?php
  session_start();
?>
<!DOCTYPE html>
<html ng-app="Index">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="1:1 Stream">
    <meta name="author" content="Benjamin Thomas">
    <title>1:1 Podcast Stream</title>
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="styles/footer.css">
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
            <li class="active"><a href="#" target="_self">Live<span class="sr-only">(current)</span></a></li>
            <li><a href="episodes.php" target="_self">Episodes</a></li>
            <?php if(isSet($_SESSION['userPermission']) && $_SESSION['userPermission'] == 1){?>
              <li><a href="manageEpisodes.php" target="_self">Manage</a></li>
            <?php } ?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if(isSet($_SESSION['userPermission']) && $_SESSION['userPermission'] == 1){?>
                <li><a href="php/logout.php" target="_self">Logout</a></li>
            <?php } else{ ?>
              <li><a data-toggle="modal" data-target="#loginModal">Login</a></li>
            <?php } ?>
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
    <div class="navbar-bottom">
      <h6>&copy;2015 Ben Thomas, Collin Enders</h6>
    </div>
    <!-- Stream Offline -->
    <div class="modal fade" id="offlineModal" tabindex="-1" role="dialog" aria-labelledby="offlineModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="offlineModalLabel">Uh-oh!</h4>
          </div>
          <div class="modal-body">
            It looks like this stream is offline. Would you like to view previous episodes?
            <div>
              <br>
              <div class="button-group pull-right">
                <a href="episodes.php"><button type="button" class="btn btn-success">Yes</button></a>
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" ng-controller="loginCtrl" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="loginModalLabel">Login</h4>
          </div>
          <div class="modal-body">
            <form ng-submit="login()">
              <div class="form-group">
                <label>Email address</label>
                <input type="email" class="form-control" ng-model="email" name="email" placeholder="Email">
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" ng-model="password" name="password" placeholder="Password">
              </div>
              <div class="form-group">
                <div class="btn-group pull-right">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-success">Login</button>
                </div>
                <div class="clearfix"></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/angular.min.js"></script>
  <script type="text/javascript" src="js/angular-animate.min.js"></script>
  <script type="text/javascript" src="js/angular-toastr.min.js"></script>
  <script type="text/javascript" src="js/angular-toastr.tpls.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/sha256.js"></script>
  <script type="text/javascript" src="js/video.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    var videoPlayer = videojs('videoStream');
    var err;
    videoPlayer.on('error', function(event){
      if(event.target.outerText != null || event.target.outerText == "↵FLASH: rtmpconnectfailure"){
        $('#offlineModal').modal('show');
      }});
  });
  videojs.options.flash.swf = "video-js.swf";

  var app = angular.module('Index', [
    'toastr',
    'ngAnimate'
  ]);

  app.config([
    '$locationProvider',
    '$animateProvider',
    'toastrConfig',
    function($locationProvider, $animateProvider, toastrConfig) {
      $animateProvider.classNameFilter(/animate/);
      $locationProvider.html5Mode({
        enabled: true,
        requireBase: false
      });

      angular.extend(toastrConfig, {
        toastClass: 'toast animate'
      });
    }
  ]);

  app.controller('loginCtrl', [
    '$scope',
    '$http',
    'toastr',
    function($scope, $http, toastr) {

      $scope.login = function() {
        if(!$scope.email || $scope.email === ' ' ||
         !$scope.password || $scope.password === ' ') {
          return;
        }

        var hasher = new jsSHA("SHA-256", "TEXT");
        hasher.update($scope.password);
        var passwordHash = hasher.getHash("HEX");

        var params = {
          'email': $scope.email,
          'passwordHash': passwordHash
        };

        $http({
          method: 'POST',
          url: 'php/login.php',
          data: $.param(params),
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data) {
          if(data.success) {
            window.location.reload();
          }
          else {
            toastr.warning(data.message, 'Invalid Login');
            console.log('loginCtrl - login', data.info);
          }
        }).error(function(data) {
          var responce = typeof(data) !== "undefined" && data.message ? data.message : 'There was a problem updating the Episode.'
          toastr.warning(responce, 'Problem Logging In.');
          console.error('loginCtrl Error - login: ', arguments);
        })
      }
    }
  ]);
  </script>
</html>
