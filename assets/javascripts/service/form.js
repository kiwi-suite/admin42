angular.module('admin42')
    .factory('$formService', ['$cacheFactory', 'jsonCache', function($cacheFactory, jsonCache) {
        var hash = {}
        function getFormFactory(formId) {
            var name = "formService" + formId;

            if (!hash.hasOwnProperty(name)) {
                hash[name] = $cacheFactory(name);
            }
            return hash[name];
        }

        return {
            get: function(formId, key) {
                return jsonCache.get(getFormFactory(formId).get(key));
            },
            put: function (formId, key, value) {
                return getFormFactory(formId).put(key, value);
            },
            remove: function(formId, key) {
                return getFormFactory(formId).remove(key);
            },
            removeAll: function(formId) {
                return getFormFactory(formId).removeAll();
            },
            destroy: function(formId) {
                return getFormFactory(formId).destroy();
            },
            info: function(formId) {
                return getFormFactory(formId).info();
            }
        }
    }]);
