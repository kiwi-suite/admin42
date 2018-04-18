angular.module('admin42')
    .directive('formText', [function () {
        return {
            restrict: 'E',
            templateUrl: function (elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@'
            },
            controller: [
                '$scope', '$rootScope', 'jsonCache', '$formService',
                function ($scope, $rootScope, jsonCache, $formService) {
                    $scope.formData = jsonCache.get($scope.elementDataId);

                    $scope.onChange = function () {
                        $rootScope.$broadcast('formElementChange', $scope.formData.name);
                        $scope.formData.errors = [];
                    };

                    $scope.onBlur = function () {
                        $rootScope.$broadcast('formElementBlur', $scope.formData.name);
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
