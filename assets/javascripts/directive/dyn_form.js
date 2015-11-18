angular.module('admin42')
    .directive('adminDynamicForm', function() {
        return {
            restrict: 'A',
            scope: {
                templateName: '@',
                adminDynamicFormElements: '@',
                adminDynamicPrototypes: '@',
                parentHashes: '='
            },
            templateUrl: function(elem,attrs){
                return attrs.baseForm;
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.data = {};
                $scope.elements = angular.copy(jsonCache.get($scope.adminDynamicFormElements));
                $scope.prototypes = jsonCache.get($scope.adminDynamicPrototypes);

                $scope.data.selectedPrototype = $scope.prototypes[0];

                $scope.makeid = function(){
                    var text = "";
                    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                    for( var i=0; i<8; i++ ){
                        text += possible.charAt(Math.floor(Math.random() * possible.length));
                    }
                    return "ph" + text;
                };

                $scope.addTemplate = function() {
                    var element = angular.copy($scope.data.selectedPrototype);
                    element.hash = $scope.makeid();
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
    });
