angular.module('admin42')
    .controller('LinkDialogController', ['$scope', '$http', function ($scope, $http) {
        $scope.includeArray = [];
        $scope.linkSelection = {
            type: null,
            value: null
        };


        var linkValue = null;

        $scope.link = {
            setValue: function(value) {
                linkValue = value;
                $scope.linkSelection.value = value;
            },
            getValue: function(){
                return linkValue;
            }
        };

        $scope.change = function(){
            if ($scope.linkType.length > 0) {
                $scope.includeArray = ['/link/' + $scope.linkType + '.html'];

                $scope.linkSelection.type = $scope.linkType;

                return;
            }

            $scope.includeArray = [];
        };

        $scope.ok = function () {
            if (linkValue == null || $scope.linkType.length == 0) {

                return;
            }

            $http({
                method: "POST",
                url: linkSaveUrl,
                data: {
                    type: $scope.linkType,
                    value: linkValue,
                    id: linkId
                }
            })
                .success(function (data){

                })
                .error(function (){
                });

        };

        $scope.cancel = function () {
        };
    }]);