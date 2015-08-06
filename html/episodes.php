<?php
  session_start();
?>
<!DOCTYPE html>
<html ng-app="viewEpisodes">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="1:1 Stream">
    <meta name="author" content="Benjamin Thomas, Collin Enders">
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
    <div class="container" ng-controller="EpisodeCtrl">
      <h1 class="page-header">1:1 Podcast Episodes</h1>
      <br>
      <div class="input-group">
        <input ng-model="query" type="text" class="form-control" placeholder="Search {{action}}...">
        <div class="input-group-btn">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{action}} <span class="caret"></span></button>
          <ul class="dropdown-menu dropdown-menu-right">
            <li><a ng-click="changeAction('Title')" href="#">Title</a></li>
            <li><a ng-click="changeAction('Tags')" href="#">Tags</a></li>
            <li><a ng-click="changeAction('Everything')" href="#">Everything</a></li>
          </ul>
        </div>
      </div>
      <br>
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div dir-paginate="episode in episodes | filter:filterEposides | orderBy:'-id' | itemsPerPage: 5" ng-cloak>
          <div class="panel panel-default">
            <div class="panel-heading" id="heading{{episode.id}}">
              <p class="panel-title pull-left">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{episode.id}}">
                  {{episode.title}}
                </a>
              </p>
              <div class="pull-right btn-group">
                <a class="btn btn-info btn-xs" data-toggle="collapse" data-parent="#accordion" href="#collapse{{episode.id}}">Info</a>
                <a class="btn btn-primary btn-xs" href="episode.php?id={{episode.id}}">View</a>
              </div>
              <div class="clearfix"></div>
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
      <div class="text-center">
        <dir-pagination-controls template-url="dirPagination.tpl.html"></dir-pagination-controls>
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
                <label>User Name</label>
                <input type="text" class="form-control" ng-model="username" name="username" placeholder="User Name">
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
  <script type="text/javascript" src="js/sha256.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script src="js/video.js"></script>
  <script type="text/javascript">
    var app = angular.module('viewEpisodes', [
      'toastr',
      'ngAnimate',
      'angularUtils.directives.dirPagination'
    ]);
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
      $scope.action = 'Title';
      $scope.query = '';

      $scope.changeAction = function(action) {
        switch(action) {
          case 'Everything':
            $scope.action = 'Everything';
          break;
          case 'Tags':
            $scope.action = 'Tags';
          break;
          case 'Title':
          default:
            $scope.action = 'Title';
        }
      }

      $scope.filterEposides = function(episode) {
        switch($scope.action) {
          case 'Everything':
            if(episode.title.toLowerCase().indexOf($scope.query.toLowerCase()) != -1)
              return true;
            else if(episode.description.toLowerCase().indexOf($scope.query.toLowerCase()) != -1)
              return true;
            else if(searchTags(episode.tags, $scope.query))
              return true;
            else
              return false;
          break;
          case 'Tags':
            return searchTags(episode.tags, $scope.query);
          break;
          case 'Title':
          default:
            if(episode.title.toLowerCase().indexOf($scope.query.toLowerCase()) != -1)
              return true;
            else
              return false;
          break;
        }
      }

      $scope.getEpisodes = function() {
        $http.get('php/getEpisodes.php').success(function(episodes) {
          $scope.episodes = episodes;
        }).error(function(data) {
          var responce = data.message ? data.message : 'There was a problem getting the Episodes.'
          toastr.warning(responce, 'Fetching Episodes Failed');
          console.error('EpisodeCtrl Error - getEpisodes:', arguments);
        });
      }

      function searchTags(tags, search) {
        var found = false;
        for(var i in tags) {
          if(tags[i].name.toLowerCase().indexOf(search.toLowerCase()) != -1) {
            found = true;
            break;
          }
        }
        return found;
      }

      $scope.getEpisodes();
    }]);
    app.controller('loginCtrl', [
      '$scope',
      '$http',
      'toastr',
      function($scope, $http, toastr) {

        $scope.login = function() {
          if(!$scope.username || $scope.username === ' ' ||
           !$scope.password || $scope.password === ' ') {
            return;
          }

          var params = {
            'email': $scope.username,
            'password': $scope.password
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
