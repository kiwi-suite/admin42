angular.module('admin42', [
    'ui.bootstrap',
    'ui.bootstrap.datetimepicker',
    'ngAnimate',
    'ngSanitize',
    'ui.utils',
    'smart-table',
    'toaster',
    'ngStorage',
    'ui.sortable',
    'ui.select',
    'angularFileUpload'
]);

angular.module('admin42').config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}]);
