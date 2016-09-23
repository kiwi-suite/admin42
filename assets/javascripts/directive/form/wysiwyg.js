angular.module('admin42')
    .directive('formWysiwyg', ['$timeout', 'jsonCache', function($timeout, jsonCache) {
        return {
            restrict: 'E',
            templateUrl: 'element/form/wysiwyg.html',
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', '$formService', function($scope, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.$on('$dynamic:sort-start', function() {
                    $scope.$broadcast('$tinyWysiwyg:disable');
                });

                $scope.$on('$dynamic:sort-stop', function() {
                    $scope.$broadcast('$tinyWysiwyg:enable');
                });

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
