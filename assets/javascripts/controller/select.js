angular.module('admin42')
    .controller('SelectController', ['$scope', '$attrs', 'jsonCache', function($scope, $attrs, jsonCache){
        $scope.items = jsonCache.get($attrs.jsonDataId);
        $scope.item = {};

        if ($attrs.initValue.length > 0) {
            angular.forEach($scope.items, function(value){
                if (value.id == $attrs.initValue) {
                    $scope.item.selected = value;
                }
            });
        }
    }]);
