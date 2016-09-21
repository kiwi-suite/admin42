angular.module('admin42')
    .directive('formMulticheckbox', ['jsonCache', function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/multicheckbox.html',
            scope: {
                formId: '@formId',
                label: '@label',
                name: '@name',
                jsonCacheId: '@jsonCacheId',
                isRequired: '@isRequired',
                valueOptions: '@valueOptions',
                errors: '@errors'
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                var value = jsonCache.get($scope.jsonCacheId);
                $scope.option = {};
                $scope.options = jsonCache.get($scope.valueOptions);
                $scope.required = ($scope.isRequired === "1");

                if ($scope.errors.length > 0) {
                    $scope.errorMessages = jsonCache.get($scope.errors);
                } else {
                    $scope.errorMessages = [];
                }



                $scope.select = function(id){
                    angular.forEach($scope.options, function(option){
                        if (option.id == id) {
                            $scope.option.selected = option;
                        }
                    });
                };

                if (value.length > 0) {
                    $scope.select(value);
                }
            }]
        }
    }]);
