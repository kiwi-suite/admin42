angular.module('admin42')
    .directive('formText', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/text.html',
            scope: {
                jsonCacheId: '@'
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);

                $scope.getName = function () {
                    return $scope.formData.name;
                }
            }]
        }
    }]);
