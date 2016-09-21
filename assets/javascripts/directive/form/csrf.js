angular.module('admin42')
    .directive('formCsrf', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/csrf.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);
            }]
        }
    }]);
