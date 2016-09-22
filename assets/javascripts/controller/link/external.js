angular.module('admin42')
    .controller('LinkExternController',['$scope', function($scope){
            var inititalValue = $scope.link.getValue();
            if (inititalValue != null) {
                $scope.externType = inititalValue.type;
                $scope.externUrl = inititalValue.url;
            } else {
                $scope.externType = "http://";
            }

            $scope.change = function() {
                $scope.link.setValue({
                    type: $scope.externType,
                    url: $scope.externUrl
                });
            }
        }]
    );
