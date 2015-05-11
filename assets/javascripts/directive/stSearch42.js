/**
 * modified stSearch directive for smart-tables
 * remove as soon as fixes are available from original vendor
 * fixes: multiple preset search input values
 */

angular.module('smart-table')
    .directive('stSearch42', ['stConfig', '$timeout', function (stConfig, $timeout) {
        return {
            require: '^stTable',
            link: function (scope, element, attr, ctrl) {
                var tableCtrl = ctrl;
                var promise = null;
                var throttle = attr.stDelay || stConfig.search.delay;

                attr.$observe('stSearch42', function (newValue, oldValue) {
                    var input = element[0].value;
                    if (newValue !== oldValue && input) {
                        //ctrl.tableState().search = {}; // TODO: @lcs: this prevents multiple preset values from working
                        tableCtrl.search(input, newValue);
                    }
                });

                //table state -> view
                scope.$watch(function () {
                    return ctrl.tableState().search;
                }, function (newValue, oldValue) {
                    var predicateExpression = attr.stSearch42 || '$';

                    console.log(predicateExpression, element[0].value, element[0]);

                    if (newValue.predicateObject && newValue.predicateObject[predicateExpression] !== element[0].value) {
                        element[0].value = newValue.predicateObject[predicateExpression] || '';
                    }
                }, true);

                // view -> table state
                element.bind('input', function (evt) {
                    evt = evt.originalEvent || evt;
                    if (promise !== null) {
                        $timeout.cancel(promise);
                    }

                    promise = $timeout(function () {
                        tableCtrl.search(evt.target.value, attr.stSearch42 || '');
                        promise = null;
                    }, throttle);
                });
            }
        };
    }]);
