angular.module('admin42')
    .directive('formMulticheckbox', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);
                var initialValues = $scope.formData.value;
                console.log(initialValues);
                $scope.values = [];
                $scope.options = $scope.formData.valueOptions;
                $scope.checkboxModel = {};

                angular.forEach($scope.options, function(option){
                    $scope.checkboxModel[option.id] = (initialValues.indexOf(option.id) != -1);
                });

                var initial = true;
                $scope.select = function(){
                    var values = [];
                    angular.forEach($scope.checkboxModel, function(value, index){
                        if (value === false) {
                            return;
                        }
                        values.push(index)
                    });
                    $scope.values = values;

                    if (initial === false) {
                        $scope.formData.errors = [];
                    }
                    initial = false;
                };

                $scope.select();

                if (angular.isDefined($scope.formData.options.formServiceHash)) {
                    $formService.put(
                        $scope.formData.options.formServiceHash,
                        $scope.formData.name,
                        $scope.elementDataId
                    );
                }
            }]
        }
    }]);
