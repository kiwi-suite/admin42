angular.module('admin42')
    .factory('jsonCache', ['$cacheFactory', function($cacheFactory) {
        return $cacheFactory('json-cache');
    }])
    .directive('script', ['jsonCache', function(jsonCache) {
        return {
            restrict: 'E',
            terminal: true,
            compile: function(element, attr) {
                if (attr.type == 'application/json') {
                    var jsonHandler = attr.id,
                        json = angular.fromJson(element[0].text);
                    jsonCache.put(jsonHandler, json);
                }
            }
        };
    }]);
