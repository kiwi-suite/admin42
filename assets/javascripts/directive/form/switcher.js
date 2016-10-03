angular.module('admin42')
    .directive('formSwitcher', [function() {
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

                $scope.model = ($scope.formData.value == $scope.formData.checkedValue);

                $scope.onChange = function () {
                    $scope.formData.value = ($scope.model == true) ? $scope.formData.checkedValue : $scope.formData.uncheckedValue;
                    $scope.formData.errors = [];
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
