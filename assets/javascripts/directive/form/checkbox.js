angular.module('admin42')
    .directive('formCheckbox', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/checkbox.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);

                $scope.model = ($scope.formData.value == $scope.formData.checkedValue);

                $scope.onChange = function () {
                    $scope.formData.value = ($scope.model == true) ? $scope.formData.checkedValue : $scope.formData.uncheckedValue;
                };
            }]
        }
    }]);
