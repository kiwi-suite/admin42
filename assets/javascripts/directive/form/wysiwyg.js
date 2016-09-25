angular.module('admin42')
    .directive('formWysiwyg', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', '$formService', 'jsonCache', function($scope, $formService, jsonCache) {
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
