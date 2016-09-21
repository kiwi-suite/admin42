angular.module('admin42')
    .directive('formSelect', ['jsonCache', function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/select.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);
                $scope.option = {};
                $scope.options = $scope.formData.valueOptions;

                $scope.select = function(id){
                    angular.forEach($scope.options, function(option){
                        if (option.id == id) {
                            $scope.option.selected = option;
                        }
                    });
                };

                if ($scope.formData.value.length > 0) {
                    $scope.select($scope.formData.value);
                }
            }]
        }
    }]);
