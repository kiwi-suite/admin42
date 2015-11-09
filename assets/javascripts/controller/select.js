angular.module('admin42')
    .controller('SelectController', ['$scope', '$attrs', 'jsonCache', function($scope, $attrs, jsonCache){
        $scope.options = jsonCache.get($attrs.jsonDataId);
        $scope.option = {};
        
        $scope.select = function(id){
            angular.forEach($scope.options, function(option){
                if (option.id == id) {
                    $scope.option.selected = option;
                }
            });
        };
        
        if ($attrs.initValue && $attrs.initValue.length > 0) {
            $scope.select($attrs.initValue);
        }
    }]);
