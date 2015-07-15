<!DOCTYPE html>
<html ng-app="ManageVideos">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="1:1 Stream">
    <meta name="author" content="Benjamin Thomas">
    <title>Manage Videos</title>
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="styles/bootstrap-theme.min.css">
    <link rel="stylesheet" href="styles/angular-toastr.min.css">
  </head>
  <body>
    <div class="container" ng-controller="EpisodeCtrl">
      <h1 class="page-header">1:1 Podcasts Episode Editor</h1>
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#new" data-toggle="tab">New</a></li>
        <li role="presentation"><a href="#list" data-toggle="tab">List</a></li>
      </ul>
      <br>
      <div class="tab-content">
        <div class="tab-pane active" id="new">
          <form id="create" ng-submit="createEpisode()">
            <legend>New Episode</legend>
            <div class="form-group">
              <label>File</label>
              <div class="row">
                <div class="col-xs-10">
                  <select ng-model="filename" class="form-control" name="filename" ng-options="filename for filename in filenames">
                    <option value="">--</option>
                  </select>
                </div>
                <div class="col-xs-2">
                  <button ng-click="getFilenames()" class="btn btn-info pull-right">Refresh</button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Title</label>
              <input ng-model="title" class="form-control" type="text" name="title" placeholder="Title">
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea ng-model="description" class="form-control" name="description" rows="3" style="resize: vertical" placeholder="Description"></textarea>
            </div>
            <div class="form-group">
              <input class="btn btn-primary" type="submit" value="Create">
            </div>
          </form>
        </div>
        <div class="tab-pane" id="list">
          <div class="form-group">
            <input ng-model="query" type="text" class="form-control" placeholder="Search">
          </div>
          <div ng-repeat="episode in episodes | filter:query">
            <form ng-submit="updateEpisode(episode); edit = true">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <div class="panel-title">
                    <div class="row">
                      <div class="col-xs-10">
                        <div style="font-size: 1.5em;" ng-show="edit">{{episode.title}}</div>
                        <input ng-hide="edit" class="form-control" type="text" name="description" ng-model="episode.title" placeholder="Title">
                      </div>
                      <div class="col-xs-2">
                        <input type="button" ng-show="edit" ng-click="edit = !edit" ng-init="edit = true" class="btn btn-info pull-right" value="Edit">
                        <input type="button" ng-hide="edit" ng-click="edit = !edit" ng-init="edit = true" class="btn btn-default pull-right" value="Cancel">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="panel-body">
                  <label>Description</label>
                  <p ng-show="edit">{{episode.description}}</p>
                  <input ng-hide="edit" class="form-control" type="text" name="description" ng-model="episode.description" placeholder="Description">
                  <label>Filename</label>
                  <p ng-show="edit">{{episode.filename}}</p>
                  <div ng-hide="edit" class="row">
                    <div class="col-xs-10">
                      <select ng-model="episode.filename" class="form-control" name="filename" ng-options="filename for filename in filenames">
                        <option value="">--</option>
                      </select>
                      <br>
                    </div>
                    <div class="col-xs-2">
                      <button ng-click="getFilenames()" class="btn btn-info pull-right">Refresh</button>
                    </div>
                  </div>
                  <div ng-hide="edit" class="form-group">
                    <input class="btn btn-primary" type="submit" value="Update">
                  </div>
                </div>
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
  <script type="text/javascript">
    var app = angular.module('ManageVideos', ['toastr', 'ngAnimate']);
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
      $scope.filenames = [];
      $scope.episodes = [];

      $scope.getFilenames = function() {
        $http.get('php/getVideos.php').success(function(filenames) {
          $scope.filenames = filenames;
        }).error(function(data) {
          var responce = data.message ? data.message : 'There was a problem getting the Video Filenames.'
          toastr.warning(responce, 'Fetching Filnames Failed');
          console.error('EpisodeCtrl Error - getFilenames:', arguments);
        })
      }

      $scope.createEpisode = function() {
        var params = {
          filename: $scope.filename,
          title: $scope.title,
          description: $scope.description
        };
        console.log(params);
        $http({
          method: 'POST',
          url: 'php/createEpisode.php',
          data: $.param(params),
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(episode) {
          var button = '<a class="btn btn-success" href="#' + episode.id +'" target="_self">View</a>';
          toastr.success('<p>The Episode was successfully created.</p>' + button, 'Episode Created', {
            allowHtml: true
          });
          $scope.episodes.push(episode);
          console.log(episode);
        }).error(function(data) {
          var responce = data.message ? data.message : 'There was a problem creating the Episode.'
          toastr.warning(responce, 'Episode Creation Failed');
          console.error('EpisodeCtrl Error - createEpisode: ', arguments);
        });
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

      $scope.updateEpisode = function(episode) {
        console.log(episode);
        $http({
          method: 'POST',
          url: 'php/updateEpisode.php',
          data: $.param(episode),
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(episode) {
          var button = '<a class="btn btn-success" href="#' + episode.id +'" target="_self">View</a>';
          toastr.success('<p>The Episode was successfully created.</p>' + button, 'Episode Created', {
            allowHtml: true
          });
          console.log(episode);
        }).error(function(data) {
          var responce = data.message ? data.message : 'There was a problem creating the Episode.'
          toastr.warning(responce, 'Episode Creation Failed');
          console.error('EpisodeCtrl Error - createEpisode: ', arguments);
        });
      }

      $scope.getFilenames();
      $scope.getEpisodes();
    }]);
  </script>
</html>
