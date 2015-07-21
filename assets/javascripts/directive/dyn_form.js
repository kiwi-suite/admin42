angular.module('admin42')
    .directive('adminDynamicForm', [function() {
        return {
            restrict: 'A',
            scope: {
                templateName: '@',
                adminDynamicFormElements: '@',
                adminDynamicPrototypes: '@'
            },
            templateUrl: function(elem,attrs){
                return attrs.baseForm;
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.data = {};
                $scope.elements = jsonCache.get($scope.adminDynamicFormElements);
                $scope.prototypes = jsonCache.get($scope.adminDynamicPrototypes);

                $scope.data.selectedPrototype = $scope.prototypes[0];

                $scope.addTemplate = function() {
                    var element = angular.copy($scope.data.selectedPrototype);
                    element.internIndex = $scope.elements.length;
                    $scope.elements.push(element);
                };

                $scope.sortableOptions = {
                    axis: 'y',
                    opacity: 0.5,
                    handle: '.panel-sort-handle',
                    items: "> .sortable-container",
                    placeholder: "sortable-placeholder",
                    start: function() {
                        $scope.$broadcast('$tinyWysiwyg:disable');
                        $scope.$emit('$tinyWysiwyg:disable');
                    },
                    stop: function(event, ui) {
                        $scope.$broadcast('$tinyWysiwyg:enable');
                        $scope.$emit('$tinyWysiwyg:enable');
                    }
                };

            }]
        };
    }]);
