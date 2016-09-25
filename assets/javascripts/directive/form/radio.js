angular.module('admin42')
    .directive('formRadio', ['jsonCache', function() {
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
                $scope.options = $scope.formData.valueOptions;

                $scope.radioModel = $scope.formData.value;

                $scope.select = function(radioModel) {
                    $scope.formData.value = radioModel;
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
