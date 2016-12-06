angular.module('admin42', [
    'ui.bootstrap',
    'ngAnimate',
    'ngSanitize',
    'ui.validate',
    'smart-table',
    'toaster',
    'ngStorage',
    'ui.select',
    'ui.tree'
]);

angular.module('admin42').config(['$httpProvider', '$compileProvider', function($httpProvider, $compileProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
    $compileProvider.debugInfoEnabled(false);
}]);
