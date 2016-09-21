angular.module('admin42')
    .directive('formHidden', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/hidden.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);
            }]
        }
    }]);
