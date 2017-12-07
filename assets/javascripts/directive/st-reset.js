angular.module('smart-table')
    .directive('stReset', function () {
        return {
            restrict: 'EA',
            require: '^stTable',
            link: function (scope, element, attr, ctrl) {

                return element.bind('click', function() {
                    console.log('click');
                    return scope.$apply(function() {
                        var tableState;
                        tableState = ctrl.tableState();
                        tableState.search.predicateObject = {};
                        tableState.pagination.start = 0;
                        return ctrl.pipe();
                    });
                });

            }
        };
    });
