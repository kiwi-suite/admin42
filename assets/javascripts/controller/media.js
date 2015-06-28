angular.module('admin42')
    .controller('MediaController', ['$scope', 'FileUploader', '$attrs', '$http', function ($scope, FileUploader, $attrs, $http) {
        var currentTableState = {};
        var url = $attrs.url;

        $scope.isCollapsed = true;
        $scope.collection = [];
        $scope.isLoading = true;
        $scope.displayedPages = 1;

        var uploader = $scope.uploader = new FileUploader({
            url: $attrs.uploadUrl
        });

        uploader.onCompleteAll = function() {
            requestFromServer(url, currentTableState);
        };

        $scope.isImage = function(item) {
            return (item.mimeType.substr(0, 6) == "image/");
        };

        $scope.getDocumentClass = function(item) {
            return "fa-file";
        };

        $scope.callServer = function (tableState) {
            currentTableState = tableState;

            requestFromServer(url, tableState);
        };

        function requestFromServer(url, tableState) {
            $scope.collection = [];
            $scope.isLoading = true;

            $http.post(url, tableState).
                success(function(data, status, headers, config) {
                    $scope.isLoading = false;

                    $scope.collection = data.data;

                    $scope.displayedPages = data.meta.displayedPages;
                    tableState.pagination.numberOfPages = data.meta.displayedPages;
                }).
                error(function(data, status, headers, config) {
                });
        }
}]);
