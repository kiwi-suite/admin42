angular.module('admin42')
    .directive('formText', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.onChange = function () {
                    $scope.formData.errors = [];
                };

                $scope.empty = function() {
                    $scope.formData.value = "";
                    $scope.onChange();
                };

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
