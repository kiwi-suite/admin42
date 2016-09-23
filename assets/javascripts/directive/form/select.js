angular.module('admin42')
    .directive('formSelect', ['jsonCache', function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/select.html',
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);
                $scope.option = {};
                $scope.options = $scope.formData.valueOptions;

                if (angular.isDefined($scope.formData.options.formServiceHash)) {
                    $formService.put(
                        $scope.formData.options.formServiceHash,
                        $scope.formData.name,
                        $scope.elementDataId
                    );
                }

                $scope.select = function($item, $model){
                    $scope.formData.errors = [];
                    $scope.formData.value = $model.id;
                };

                if ($scope.formData.value.length > 0) {
                    angular.forEach($scope.options, function(option){
                        if (option.id == $scope.formData.value) {
                            $scope.option.selected = option;
                        }
                    });

                }
            }]
        }
    }]);
