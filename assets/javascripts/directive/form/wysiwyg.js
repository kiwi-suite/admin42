angular.module('admin42')
    .directive('formWysiwyg', ['$timeout', 'jsonCache', function($timeout, jsonCache) {
        return {
            restrict: 'E',
            templateUrl: 'element/form/wysiwyg.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', function($scope) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);

                $scope.$on('$dynamic:sort-start', function() {
                    console.log("start");
                    $scope.$broadcast('$tinyWysiwyg:disable');
                });

                $scope.$on('$dynamic:sort-stop', function() {
                    console.log("stop");
                    $scope.$broadcast('$tinyWysiwyg:enable');
                });
            }]
        }
    }]);
