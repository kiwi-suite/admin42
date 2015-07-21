angular.module('admin42').directive('dynamicModel', ['$compile', function ($compile) {
    return {
        'link': function(scope, element, attrs) {
            scope.$watch(attrs.dynamicModel, function(dynamicModel) {
                if (attrs.ngModel == dynamicModel || !dynamicModel) return;

                element.attr('ng-model', dynamicModel);
                if (dynamicModel == '') {
                    element.removeAttr('ng-model');
                }

                // Unbind all previous event handlers, this is 
                // necessary to remove previously linked models.
                element.unbind();
                $compile(element)(scope);
            });
        }
    };
}]);