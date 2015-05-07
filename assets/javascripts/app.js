angular.module('admin42', ['ui.bootstrap', 'ngAnimate', 'ui.utils', 'smart-table', 'toaster']);

angular.module('admin42').config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}]);
