angular.module('admin42')
    .directive('adminDynamicForm', [function() {
        return {
            restrict: 'A',
            scope: {
                templateName: '@',
                adminDynamicFormElements: '@',
                adminDynamicPrototypes: '@'
            },
            templateUrl: 'dynamic_form/base.html',
            controller: ['$scope', function($scope) {
                $scope.data = {};
                $scope.elements = angular.fromJson($scope.adminDynamicFormElements);
                $scope.prototypes = angular.fromJson($scope.adminDynamicPrototypes);

                $scope.data.selectedPrototype = $scope.prototypes[0];

                $scope.addTemplate = function() {
                    $scope.elements.push($scope.data.selectedPrototype);
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
