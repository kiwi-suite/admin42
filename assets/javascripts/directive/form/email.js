angular.module('admin42')
    .directive('formEmail', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/email.html',
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.onChange = function () {
                    $scope.formData.errors = [];
                }

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
