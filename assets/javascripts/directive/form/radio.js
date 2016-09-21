angular.module('admin42')
    .directive('formRadio', ['jsonCache', function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/radio.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);
                $scope.options = $scope.formData.valueOptions;

                $scope.radioModel = $scope.formData.value;

                $scope.select = function(radioModel) {
                    $scope.formData.value= radioModel;
                }
            }]
        }
    }]);
