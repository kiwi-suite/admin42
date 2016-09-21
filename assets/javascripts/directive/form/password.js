angular.module('admin42')
    .directive('formPassword', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/password.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);
            }]
        }
    }]);
