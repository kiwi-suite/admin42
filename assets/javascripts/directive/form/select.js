angular.module('admin42')
    .directive('formSelect', [function() {
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
                $scope.option = {};
                $scope.options = $scope.formData.valueOptions;

                $scope.searchEnabled = $scope.options.length > 5;

                if (angular.isDefined($scope.formData.options.formServiceHash)) {
                    $formService.put(
                        $scope.formData.options.formServiceHash,
                        $scope.formData.name,
                        $scope.elementDataId
                    );
                }
                function setValue() {
                    if ($scope.formData.value.length > 0) {
                        angular.forEach($scope.options, function(option){
                            if (option.id == $scope.formData.value) {
                                $scope.option.selected = option;
                            }
                        });

                    }
                }

                $scope.select = function($item, $model){
                    $scope.formData.errors = [];
                    $scope.formData.value = $model.id;
                };

                if ($scope.formData.value.length == 0 && $scope.formData.emptyValue !== null) {
                    $scope.formData.value = $scope.formData.emptyValue;
                }

                $scope.empty = function() {
                    $scope.formData.value = $scope.formData.emptyValue;

                    $scope.formData.errors = [];
                    setValue();
                };


                setValue();

            }]
        }
    }]);
