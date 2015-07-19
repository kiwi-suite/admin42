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
                    $scope.elements.push(angular.copy($scope.data.selectedPrototype));
                };

                $scope.sortableOptions = {
                    axis: 'y',
                    opacity: 0.5,
                    handle: '.panel-sort-handle',
                    items: "> .sortable-container",
                    placeholder: "sortable-placeholder"
                };

                $scope.getName = function(element, name, index) {
                    if (element.initial === false) {
                        return element.name + '[' + index +'][' + name + ']';
                    }
                    return element.name + '[' + name + ']';
                }
            }]
        };
    }]);
