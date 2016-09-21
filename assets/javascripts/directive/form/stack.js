angular.module('admin42')
    .directive('formStack', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/stack.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', '$templateCache', function($scope, jsonCache, $templateCache) {
                var elementData = jsonCache.get($scope.jsonCacheId);

                $scope.protoTypes = elementData.protoTypes;
                $scope.data = {
                    selectedProtoType: $scope.protoTypes[0]
                };

                $scope.treeOptions = {};
                $scope.sortingMode = false;
                $scope.elements = [];

                angular.forEach(elementData.elements, function(element){
                    var id = elementData.id + "-" + $scope.elements.length;

                    var templateName = 'element/form/stack/' + id + '.html';

                    var elementOrFieldsetData = angular.copy(jsonCache.get(element.elementData));
                    var elementOrFieldsetDataKey = 'element/form/value/' + id + '.json';

                    elementOrFieldsetData.id = id;
                    elementOrFieldsetData.name = elementData.name + '[' + elementOrFieldsetData.options.originalName + ']';

                    jsonCache.put(elementOrFieldsetDataKey, elementOrFieldsetData);
                    $templateCache.put(
                        templateName,
                        '<' + element.directive + ' json-cache-id="' + elementOrFieldsetDataKey +'"></' + element.directive + '>'
                    );

                    $scope.elements.push({
                        formName: elementOrFieldsetData.name,
                        type: elementOrFieldsetData.options.stackType,
                        name: elementOrFieldsetData.options.fieldsetName,
                        nameEditing: false,
                        template: templateName,
                        elementData: elementOrFieldsetDataKey,
                        label: elementOrFieldsetData.label,
                        deleted: elementOrFieldsetData.options.fieldsetDeleted,
                        collapsed: $scope.sortingMode,
                        collapsedState: false,
                        nodes: []
                    });
                });

                $scope.startSortingMode = function() {
                    if ($scope.sortingMode === false) {
                        $scope.$broadcast('$dynamic:sort-start');

                        $scope.sortingMode = true;
                        angular.forEach($scope.elements, function(element){
                            element.collapsedState = element.collapsed;
                            element.collapsed = true;
                        });

                        return;
                    }

                    angular.forEach($scope.elements, function(element){
                        element.collapsed = element.collapsedState;
                    });

                    $scope.$broadcast('$dynamic:sort-stop');

                    $scope.sortingMode = false;
                };
                
                $scope.addTemplate = function () {
                    var id = elementData.id + "-" + $scope.elements.length;
                    var element = $scope.data.selectedProtoType;

                    var templateName = 'element/form/stack/' + id + '.html';

                    var elementOrFieldsetData = angular.copy(jsonCache.get(element.elementData));
                    var elementOrFieldsetDataKey = 'element/form/value/' + id + '.json';

                    elementOrFieldsetData.id = id;
                    elementOrFieldsetData.options.originalName = id;
                    elementOrFieldsetData.name = elementData.name + '[' + elementOrFieldsetData['options']['originalName'] + ']';

                    jsonCache.put(elementOrFieldsetDataKey, elementOrFieldsetData);
                    $templateCache.put(
                        templateName,
                        '<' + element.directive + ' json-cache-id="' + elementOrFieldsetDataKey +'"></' + element.directive + '>'
                    );

                    $scope.elements.push({
                        formName: elementOrFieldsetData.name,
                        type: elementOrFieldsetData.options.stackType,
                        name: "",
                        nameEditing: false,
                        template: templateName,
                        elementData: elementOrFieldsetDataKey,
                        label: elementOrFieldsetData.label,
                        deleted: false,
                        collapsed: $scope.sortingMode,
                        collapsedState: false,
                        nodes: []
                    });
                }
            }]
        }
    }]);
