angular.module('admin42')
    .controller('LinkController', ['$scope', '$http', 'jsonCache', function ($scope, $http, jsonCache) {
        $scope.availableLinkTypes = jsonCache.get('link/availableAdapters.json');
        $scope.linkData = jsonCache.get('link/linkData.json');
        $scope.includeArray = [];

        $scope.change = function(){
            if ($scope.linkData.linkType.length > 0) {
                $scope.includeArray = ['link/' + $scope.linkData.linkType + '.html'];

                return;
            }

            $scope.includeArray = [];
        };

        if ($scope.linkData.linkType !== null) {
            $scope.change();
        }

        $scope.link = {
            setValue: function(value) {
                $scope.linkData.linkValue = value;
            },
            getValue: function(){
                return $scope.linkData.linkValue;
            }
        };
    }]);
