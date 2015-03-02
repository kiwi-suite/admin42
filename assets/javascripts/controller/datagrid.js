angular.module('admin42').controller('DataGridController',['$scope', function($scope){
    function generateRandomItem() {

        return {
            id: Math.floor(Math.random() * 30),
            email: 'payer'+(Math.floor(Math.random() * 20)) + '@raum42.at'
        }
    }
    $scope.collection = [];
    $scope.isLoading = true;
    $scope.itemsByPage = 2;

    $scope.callServer = function (tableState) {
        $scope.isLoading = false;
        $scope.collection = [];
        for (var j = 0; j < 2; j++) {
            $scope.collection.push(generateRandomItem());
        }

        tableState.pagination.numberOfPages = 7;
    };
}]);
