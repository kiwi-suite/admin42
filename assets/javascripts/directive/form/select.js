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
                $scope.formData.valueOption = {};

                $scope.searchEnabled = $scope.formData.valueOptions.length > 5;

                if (angular.isDefined($scope.formData.options.formServiceHash)) {
                    $formService.put(
                        $scope.formData.options.formServiceHash,
                        $scope.formData.name,
                        $scope.elementDataId
                    );
                }

                $scope.notEmpty = function() {
                    return angular.isDefined($scope.formData.value) && $scope.formData.value !== null &&
                                (angular.isNumber($scope.formData.value) || (angular.isString($scope.formData.value) && $scope.formData.value.length > 0));
                };

                $scope.select = function($item, $model){
                    $scope.formData.errors = [];
                    $scope.formData.value = $model.id;
                };

                $scope.empty = function() {
                    $scope.formData.value = $scope.formData.emptyValue;
                    $scope.formData.valueOption.selected = null;

                    $scope.formData.errors = [];
                };

                function setValue() {
                    if ($scope.notEmpty()) {
                        angular.forEach($scope.formData.valueOptions, function(option) {
                            if (option.id == $scope.formData.value) {
                                $scope.formData.valueOption.selected = option;
                            }
                        });
                    }
                }

                if (!$scope.notEmpty() && $scope.formData.emptyValue !== null) {
                    $scope.formData.value = $scope.formData.emptyValue;
                }
                setValue();
            }]
        }
    }]);
