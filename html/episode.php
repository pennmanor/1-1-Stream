<?php
  session_start();

  require dirname(__FILE__).'/php/mysql-connect.php';
  openConnection();
  if(!isset($_GET['id'])) {
    http_response_code(400);
    echo 'Bad Request';
    return;
  }
  $id = $_GET['id'];

  $query = 'SELECT e.id, e.filename, e.title, e.description, e.viewable, t.name as tagName, t.id as tagID FROM (episode e LEFT JOIN tagLink tl ON e.id = tl.episodeID) LEFT JOIN tag t on t.id = tl.tagID WHERE e.id="'.mysqli_escape_string($connection, $id).'" ORDER BY e.id';
  $result = runQuery($query);

  closeConnection();


  if(mysqli_affected_rows($connection) == 0) {
    http_response_code(404);
    echo "404";
    return;
  }

  $episode = null;

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
    if(!isset($episode)) {
      $row['tags'] = array();
      $episode = $row;
    }
    if(isset($tag)) {
      $episode['tags'][] = $tag;
    }
    unset($tag);
  }
?>

<!DOCTYPE html>
<html ng-app="Episode">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="1:1 Stream">
    <meta name="author" content="Benjamin Thomas, Collin Enders">
    <title>1:1 Podcast Episode: <?php echo $episode['title']; ?></title>
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/angular-toastr.min.css">
    <link rel="stylesheet" href="styles/video-js.css">
    <style type="text/css">
      .vjs-default-skin { color: #ffffff; }
      .vjs-default-skin .vjs-control-bar { font-size: 103%; }
      .video-js {padding-top: 56.25%;}
      .vjs-fullscreen {padding-top: 0px;}
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
          <a class="navbar-brand" href="index.php">1:1 Stream</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="index.php" target="_self">Live</a></li>
            <li><a href="episodes.php" target="_self">Episodes</a></li>
            <?php if(isSet($_SESSION['userPermission']) && $_SESSION['userPermission'] == 1){?>
              <li><a href="manage.php" target="_self">Manage</a></li>
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
      <div class="row">
        <div class="col-md-offset-2 col-md-8">
          <h1 class="page-header"><?php echo $episode['title']?></h1>
          <video class="video-js vjs-default-skin" controls
           preload="auto"
           poster="images/1to1-logo.png"
           width="auto" height="auto"
           data-setup="{}">
           <source src="videos/<?php echo $episode['filename'] ?>" type="video/mp4">
           <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
          </video>
        </div>
        <div class="col-md-offset-2 col-md-8">
          <div class="well">
            <p><?php echo $episode['description']?></p>
            <?php if(count($episode['tags']) > 0) { ?>
              <span>
                Tags:
                <?php
                foreach ($episode['tags'] as $tag) {
                  echo '<span class="label label-info">'.$tag['name'].'</span>&nbsp;';
                }
                ?>
              </span>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar-bottom">
      <h6>&copy;2015 Ben Thomas, Collin Enders</h6>
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
  <script type="text/javascript" src="js/dirPagination.js"></script>
  <script type="text/javascript" src="js/ng-tags-input.min.js"></script>
  <script type="text/javascript" src="js/sha256.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/video.js"></script>
  <script type="text/javascript">
    videojs.options.flash.swf = "video-js.swf";

    var app = angular.module('Episode', [
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
