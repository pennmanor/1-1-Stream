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
    <style>
      textarea {
        resize: vertical;
      }

      .panel-heading .episode-title {
        font-size: 1.5em;
        cursor: pointer;
      }

      .panel-heading .form-group {
        margin-bottom: 0;
      }
    </style>
  </head>
  <body>
    <div class="container" ng-controller="EpisodeCtrl">
      <h1 class="page-header">1:1 Podcast Episodes Editor</h1>
      <a class="btn btn-default pull-right" href="episodes.html">Exit</a>
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="#new" data-toggle="tab">New</a></li>
        <li role="presentation" class="active"><a href="#list" data-toggle="tab">List</a></li>
      </ul>
      <br>
      <div class="tab-content">
        <div class="tab-pane fade" id="new">
          <form id="create" name="create" ng-submit="createEpisode(create)">
            <div class="form-group" ng-class="{ 'has-error': create.filename.$invalid && create.filename.$touched}">
              <label>File</label>
              <div class="row">
                <div class="col-xs-9">
                  <select ng-model="filename" class="form-control" name="filename" ng-options="filename for filename in filenames" required>
                    <option value="">--</option>
                  </select>
                </div>
                <div class="col-xs-3">
                  <input ng-click="changeFileFilter()" class="btn btn-warning" type="button" value="Change">
                  <input ng-click="getFilenames()" type="button" class="btn btn-info pull-right" value="Refresh">
                </div>
              </div>
            </div>
            <div class="form-group" ng-class="{ 'has-error': create.title.$invalid && create.title.$touched}">
              <label>Title</label>
              <input ng-model="title" class="form-control" type="text" name="title" placeholder="Title" required>
            </div>
            <div class="form-group" ng-class="{ 'has-error': create.description.$invalid && create.description.$touched  }">
              <label>Description</label>
              <textarea ng-model="description" class="form-control" name="description" rows="3" placeholder="Description" required></textarea>
            </div>
            <div class="form-group">
              <input class="btn btn-primary" type="submit" value="Create">
            </div>
          </form>
        </div>
        <div class="tab-pane active in fade" id="list">
          <div class="form-group">
            <input ng-model="query" type="text" class="form-control" placeholder="Search">
          </div>
          <div class="btn-toolbar">
            <div class="btn-group">
              <button ng-click="reorder('id')" class="btn btn-default">Order By ID</button>
              <button ng-click="reorder('title')" class="btn btn-default">Order By Title</button>
            </div>
            <div class="btn-group">
              <button ng-click="reverseOrder()" class="btn btn-default">Reverse Order</button>
            </div>
          </div>
          <br>
          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <form  name="update" ng-submit="updateEpisode(tempEpisode); edit = true; episode = copy(tempEpisode)" class="panel panel-default" dir-paginate="episode in episodes | filter:query | orderBy:sortedBy:isReversed | itemsPerPage: 4" ng-init="tempEpisode = copy(episode)">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-10">
                    <div class="panel-title">
                      <div class="episode-title" ng-show="edit">{{episode.title}}</div>
                      <div class="form-group" ng-class="{ 'has-error': update.title.$invalid && update.title.$touched}">
                        <input ng-hide="edit" class="form-control" type="text" name="title" ng-model="tempEpisode.title" placeholder="Title" required>
                      </div>
                      <p ng-show="edit">{{episode.filename}}</p>
                      <p ng-show="edit">{{episode.description}}</p>
                    </div>
                  </div>
                  <div class="col-xs-2">
                    <input type="button" ng-show="edit" ng-click="edit = !edit" ng-init="edit = true" class="btn btn-info pull-right" value="Edit">
                    <input type="button" ng-hide="edit" ng-click="edit = !edit" ng-init="edit = true" class="btn btn-default pull-right" value="Cancel">
                  </div>
                </div>
              </div>
              <div id="panel-{{$index}}" class="panel-collapse collapse" ng-class="{ 'in':!edit }">
                <div class="panel-body">
                  <div class="form-group" ng-class="{ 'has-error': update.description.$invalid && update.description.$touched}">
                    <label>Description</label>
                    <textarea ng-hide="edit" rows="3" class="form-control" type="text" name="description" ng-model="tempEpisode.description" placeholder="Description" required></textarea>
                  </div>
                  <div class="form-group" ng-class="{ 'has-error': update.filename.$invalid && update.filename.$touched}">
                    <label>Filename</label>
                    <div ng-hide="edit" class="row">
                      <div class="col-xs-9">
                        <select ng-model="tempEpisode.filename" class="form-control" name="filename" required>
                          <option value="{{tempEpisode.filename}}" selected="">{{tempEpisode.filename}}</option>
                          <option ng-repeat="filename in filenames" value="{{filename}}">{{filename}}</option>
                        </select>
                        <br>
                      </div>
                      <div class="col-xs-3">
                        <input ng-click="changeFileFilter()" class="btn btn-warning" type="button" value="Change">
                        <button ng-click="getFilenames()" class="btn btn-info pull-right">Refresh</button>
                      </div>
                    </div>
                    <div ng-hide="edit" class="btn-toolbar">
                      <div class="btn-group">
                        <input class="btn btn-primary" type="submit" value="Update">
                      </div>
                      <div class="btn-group">
                        <input ng-click="deleteEpisode(episode, update)" class="btn btn-danger" type="button" value="Delete">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <dir-pagination-controls template-url="dirPagination.tpl.html"></dir-pagination-controls>
      </div>
    </div>
  </body>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/angular.min.js"></script>
  <script type="text/javascript" src="js/angular-animate.min.js"></script>
  <script type="text/javascript" src="js/angular-toastr.min.js"></script>
  <script type="text/javascript" src="js/angular-toastr.tpls.min.js"></script>
  <script type="text/javascript" src="js/dirPagination.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript">
    var app = angular.module('ManageVideos', ['toastr', 'ngAnimate', 'angularUtils.directives.dirPagination']);
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

      //pagination
      $scope.isReversed = true;
      $scope.sortedBy = 'id';

      $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
      };

      var fileFilter = 'NOT_IN_DB';

      $scope.changeFileFilter = function() {
        fileFilter = fileFilter === 'NOT_IN_DB' ? '': 'NOT_IN_DB';
        $scope.getFilenames();
      }

      $scope.reorder = function(sort) {
        $scope.sortedBy = sort;
      }

      $scope.reverseOrder = function() {
        $scope.isReversed = !$scope.isReversed;
      }

      $scope.copy = function (item) {
        return angular.copy(item);
      }

      $scope.getFilenames = function() {
        $http.get('php/getVideos.php?filter=' + fileFilter).success(function(filenames) {
          $scope.filenames = filenames;
        }).error(function(data) {
          var responce = typeof(data) !== "undefined" && data.message ? data.message : 'There was a problem getting the Video Filenames.'
          toastr.warning(responce, 'Fetching Filnames Failed');
          console.error('EpisodeCtrl Error - getFilenames:', arguments);
        })
      }

      $scope.createEpisode = function(form) {
        if($scope.filename === '' || $scope.title === '' || $scope.description == '') {
          return;
        }
        var params = {
          filename: $scope.filename,
          title: $scope.title,
          description: $scope.description
        };

        $http({
          method: 'POST',
          url: 'php/createEpisode.php',
          data: $.param(params),
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          transformResponse: appendTransform($http.defaults.transformResponse, function(value) {
            console.log(value);
            return value;
          })
        }).success(function(episode) {
          console.log(episode);
          var button = '<a class="btn btn-success" href="episode.php?id=' + episode.id +'" target="_self">View</a>';
          toastr.success('<p>The Episode was successfully created.</p>' + button, 'Episode Created', {
            allowHtml: true
          });

          if(form) {
            form.$setPristine();
            form.$setUntouched();
          }

          $scope.filename = '';
          $scope.title = '';
          $scope.description = '';
          $scope.episodes.push(episode);
          $scope.getFilenames();
        }).error(function(data) {
          var responce = typeof(data) !== "undefined" && data.message ? data.message : 'There was a problem creating the Episode.'
          toastr.warning(responce, 'Episode Creation Failed');
          console.error('EpisodeCtrl Error - createEpisode: ', arguments);
        });
      }

      $scope.getEpisodes = function() {
        $http.get('php/getEpisodes.php').success(function(episodes) {
          $scope.episodes = episodes;
        }).error(function(data) {
          var responce = data && data.message ? data.message : 'There was a problem getting the Episodes.'
          toastr.warning(responce, 'Fetching Episodes Failed');
          console.error('EpisodeCtrl Error - getEpisodes:', arguments);
        });
      }

      $scope.updateEpisode = function(episode, form) {
        if(episode.filename === '' || episode.title === '' || episode.description == '') {
          return;
        }

        $http({
          method: 'POST',
          url: 'php/updateEpisode.php',
          data: $.param(episode),
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          transformResponse: appendTransform($http.defaults.transformResponse, function(value) {
            console.log(value);
            return value;
          })
        }).success(function(episode) {
          var button = '<a class="btn btn-success" href="episode.php?id=' + episode.id +'" target="_self">View</a>';
          toastr.success('<p>The Episode was successfully updated.</p>' + button, 'Episode Updated', {
            allowHtml: true
          });
          $scope.getFilenames();
        }).error(function(data) {
          var responce = typeof(data) !== "undefined" && data.message ? data.message : 'There was a problem updating the Episode.'
          toastr.warning(responce, 'Episode Update Failed');
          console.error('EpisodeCtrl Error - updateEpisode: ', arguments);
        });
      }

      $scope.deleteEpisode = function(episode) {
        $http({
          method: 'POST',
          url: 'php/deleteEpisode.php',
          data: $.param({id: episode.id}),
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          transformResponse: appendTransform($http.defaults.transformResponse, function(value) {
            console.log(value);
            return value;
          })
        }).success(function(data) {
          var index = $scope.episodes.indexOf(episode);
          $scope.episodes.splice(index, 1);
          toastr.success('<p>The Episode was successfully deleted.</p>', 'Episode Deleted', {
            allowHtml: true
          });
          $scope.getFilenames();
        }).error(function(data) {
          var responce = typeof(data) !== "undefined" && data.message ? data.message : 'There was a problem updating the Episode.'
          toastr.warning(responce, 'Episode Update Failed');
          console.error('EpisodeCtrl Error - deleteEpisode: ', arguments);
        });
      }

      function appendTransform(defaults, transform) {

        // We can't guarantee that the default transformation is an array
        defaults = angular.isArray(defaults) ? defaults : [defaults];

        // Append the new transformation to the defaults
        defaults.unshift(transform);
        return defaults;
      }

      $scope.getFilenames();
      $scope.getEpisodes();
    }]);
  </script>
</html>
