angular.module('admin42')
    .directive('formTextarea', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/textarea.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);
            }]
        }
    }]);
