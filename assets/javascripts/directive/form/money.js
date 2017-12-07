angular.module('admin42')
    .directive('formMoney', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@'
            },
            controller: ['$scope', 'jsonCache', '$formService', 'appConfig', function($scope, jsonCache, $formService, appConfig) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.onChange = function () {
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