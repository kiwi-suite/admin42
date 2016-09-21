angular.module('admin42')
    .directive('formFieldset', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/fieldset.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', '$templateCache', function($scope, jsonCache, $templateCache) {
                var elementData = jsonCache.get($scope.jsonCacheId);
                $scope.elements = [];

                angular.forEach(elementData.elements, function(element){
                    var id = elementData.id + "-" + $scope.elements.length;

                    var templateName = 'element/form/fieldset/' + id + '.html';

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
                        template: templateName,
                        elementData: elementOrFieldsetDataKey
                    });
                });


            }]
        }
    }]);
