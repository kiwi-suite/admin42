angular.module('admin42')
    .directive('formMulticheckbox', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/multicheckbox.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);
                var initialValues = $scope.formData.value;
                $scope.values = [];
                $scope.options = $scope.formData.valueOptions;
                $scope.checkboxModel = {};

                angular.forEach($scope.options, function(option){
                    $scope.checkboxModel[option.id] = (initialValues.indexOf(option.id) != -1);
                });

                $scope.select = function(){
                    var values = [];
                    angular.forEach($scope.checkboxModel, function(value, index){
                        if (value === false) {
                            return;
                        }
                        values.push(index)
                    });
                    $scope.values = values;
                };

                $scope.select();
            }]
        }
    }]);
