angular.module('admin42')
    .directive('formPassword', [function () {
        return {
            restrict: 'E',
            templateUrl: function (elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: [
                '$scope', '$rootScope', 'jsonCache', '$formService',
                function ($scope, $rootScope, jsonCache, $formService) {
                    $scope.formData = jsonCache.get($scope.elementDataId);

                    $scope.showPassword = false;

                    $scope.onChange = function () {
                        $rootScope.$broadcast('formElementChange', $scope.formData.name);
                        $scope.formData.errors = [];
                    };

                    $scope.empty = function () {
                        $scope.formData.value = "";
                        $scope.onChange();
                    };

                    $scope.preventEnter = function ($event) {
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
