angular.module('admin42')
    .directive('formFieldset', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/fieldset.html',
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$templateCache', '$formService', function($scope, jsonCache, $templateCache, $formService) {
                var elementData = jsonCache.get($scope.elementDataId);
                $scope.elements = [];

                angular.forEach(elementData.elements, function(element){
                    var id = elementData.id + "-" + $scope.elements.length;

                    var templateName = 'element/form/fieldset/' + id + '.html';

                    var elementOrFieldsetData = angular.copy(jsonCache.get(element.elementDataId));
                    var elementOrFieldsetDataKey = 'element/form/value/' + id + '.json';

                    elementOrFieldsetData.id = id;
                    elementOrFieldsetData.name = elementData.name + '[' + elementOrFieldsetData.options.originalName + ']';

                    jsonCache.put(elementOrFieldsetDataKey, elementOrFieldsetData);
                    $templateCache.put(
                        templateName,
                        '<' + element.directive + ' element-data-id="' + elementOrFieldsetDataKey +'"></' + element.directive + '>'
                    );

                    $scope.elements.push({
                        template: templateName,
                        elementDataId: elementOrFieldsetDataKey
                    });
                });

                if (angular.isDefined(elementData.options.formServiceHash)) {
                    $formService.put(
                        elementData.options.formServiceHash,
                        elementData.name,
                        $scope.elementDataId
                    );
                }
            }]
        }
    }]);
