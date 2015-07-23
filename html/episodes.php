<?php
  session_start();
?>
<!DOCTYPE html>
<html ng-app="viewEpisodes">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="1:1 Stream">
    <meta name="author" content="Benjamin Thomas">
    <title>Episodes</title>
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/angular-toastr.min.css">
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
            <li class="active"><a href="#" target="_self">Episodes<span class="sr-only">(current)</span></a></li>
            <li><a href="manageEpisodes.php" target="_self">Manage</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if(isSet($_SESSION['userPermission']) && $_SESSION['userPermission'] == 1){?>
                <li><a href="php/logout.php">Logout</a></li>
            <?php } else{ ?>
              <li><a data-toggle="modal" data-target="#loginModal">Login</a></li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container" ng-controller="EpisodeCtrl">
      <h1 class="page-header">1:1 Podcast Episodes</h1>
      <br>
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div ng-repeat="episode in episodes | filter:query | orderBy:'-id'" ng-init="tempEpisode = copy(episode)">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading{{episode.id}}">
              <h class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{episode.id}}" aria-expanded="false" aria-controls="collapse{{episode.id}}">
                  {{episode.title}}
                </a>
              </h4>
              <a class="btn btn-primary btn-xs pull-right" href="episode.php?id={{episode.id}}">View</a>
            </div>
            <div id="collapse{{episode.id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{episode.id}}">
              <div class="panel-body">
                <p>{{episode.description}}</p>
                <div ng-if="episode.tags.length > 0">
                  <label>Tags:</label>
                  <span ng-repeat="tag in episode.tags"><span class="label label-info">{{tag.name}}</span>&nbsp;</span>
                </div>
              </div>
            </div>
          </div>
          <br>
        </div>
      </div>
    </div>
    <div class="navbar-bottom">
      <h6>&copy;2015 Ben Thomas, Collin Enders</h6>
    </div>
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="loginModalLabel">Login</h4>
          </div>
          <div class="modal-body">
            <form action="php/login.php" method=POST>
              <div class="form-group">
                <label>Email address</label>
                <input type="email" class="form-control" name="email" placeholder="Email">
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="passwordHash" placeholder="Password">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success pull-right">Login</button>
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Cancel</button>
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
  <script src="js/video.js"></script>
  <script type="text/javascript">
    var app = angular.module('viewEpisodes', ['toastr', 'ngAnimate']);
    app.config(['$locationProvider', '$animateProvider', 'toastrConfig', function($locationProvider, $animateProvider, toastrConfig) {
      $animateProvider.classNameFilter(/animate/);
      $locationProvider.html5Mode({
        enabled: true,
        requireBase: false
      });

      angular.extend(toastrConfig, {
        toastClass: 'toast animate'
      });
    }]);
    app.controller('EpisodeCtrl', ['$scope', '$http', 'toastr', function($scope, $http, toastr) {
      $scope.episodes = [];

      $scope.getEpisodes = function() {
        $http.get('php/getEpisodes.php').success(function(episodes) {
          $scope.episodes = episodes;
        }).error(function(data) {
          var responce = data.message ? data.message : 'There was a problem getting the Episodes.'
          toastr.warning(responce, 'Fetching Episodes Failed');
          console.error('EpisodeCtrl Error - getEpisodes:', arguments);
        });
      }

      $scope.getEpisodes();
    }]);
  </script>
</html>
