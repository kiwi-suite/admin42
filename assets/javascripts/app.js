angular.module('admin42', ['ui.bootstrap', 'ngAnimate', 'ngSanitize', 'ui.utils', 'smart-table', 'toaster', 'ngStorage', 'ui.sortable', 'ui.select']);

angular.module('admin42').config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}]);
