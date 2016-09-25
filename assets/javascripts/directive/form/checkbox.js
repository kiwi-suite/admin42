angular.module('admin42')
    .directive('formCheckbox', 'jsonCache', [function(jsonCache) {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', '$formService', function($scope, $formService) {
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
