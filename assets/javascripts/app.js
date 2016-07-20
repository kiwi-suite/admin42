angular.module('admin42', [
    'ui.bootstrap',
    'ui.bootstrap.datetimepicker',
    'ngAnimate',
    'ngSanitize',
    'ui.validate',
    'ui.indeterminate',
    'ui.mask',
    'ui.event',
    'ui.scroll',
    'ui.scrollpoint',
    'smart-table',
    'toaster',
    'ngStorage',
    'ui.sortable',
    'ui.select'
]);

angular.module('admin42').config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}]);
