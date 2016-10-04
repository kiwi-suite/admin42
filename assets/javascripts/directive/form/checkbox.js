angular.module('admin42')
    .directive('formCheckbox', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', '$formService', 'jsonCache', function($scope, $formService, jsonCache) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.model = ($scope.formData.value == $scope.formData.checkedValue);

                $scope.onChange = function () {
                    setValue();
                    $scope.formData.errors = [];
                };

                function setValue() {
                    $scope.formData.value = ($scope.model == true) ? $scope.formData.checkedValue : $scope.formData.uncheckedValue;
                }

                setValue();

                $scope.preventEnter = function($event) {
                    if ($event.keyCode != 13) {
                        return;
                    }
                    $event.preventDefault();
                };

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
