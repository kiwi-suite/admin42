angular.module('admin42')
    .directive('formPassword', [function() {
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

                $scope.showPassword = false;

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

                $scope.togglePassword = function () {
                    $scope.showPassword = !$scope.showPassword;
                };
                
                $scope.getInputType = function () {
                    return ($scope.showPassword) ? 'text' : 'password';
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
