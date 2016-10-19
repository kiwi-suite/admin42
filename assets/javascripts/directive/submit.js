angular.module('admin42')
    .directive('submit', [function() {
        return {
            restrict: 'E',
            replace: true,
            scope: {},
            templateUrl: 'element/submit.html',
            link: function(scope, elem, attrs, ctrl) {
                scope.icon = "fa fa-save";
                if (!angular.isUndefined(attrs.icon)) {
                    scope.icon = attrs.icon;
                }

                scope.btnClass = "btn-primary";
                if (!angular.isUndefined(attrs.btnClass)) {
                    scope.btnClass = attrs.btnClass;
                }

                scope.submitText = "Save";
                if (!angular.isUndefined(attrs.submitText)) {
                    scope.submitText = attrs.submitText;
                }

                scope.submitLoading = false;

                elem.bind('click', function(event) {
                    if (scope.submitLoading == true) {
                        event.preventDefault();

                        return;
                    }
                    scope.$apply(
                        function(){
                            scope.submitLoading = true;
                        }
                    );
                });
            }
        };
    }]);
