angular.module('admin42')
    .controller('MediaController', ['$scope', 'FileUploader', '$attrs', '$http', 'toaster', 'MediaService', function ($scope, FileUploader, $attrs, $http, toaster, MediaService) {
        var currentTableState = {};
        var url = $attrs.url;

        $scope.isCollapsed = true;
        $scope.collection = [];
        $scope.isLoading = true;
        $scope.displayedPages = 1;

        $scope.errorFiles = [];

        $scope.category = $attrs.category;

        var uploader = $scope.uploader = new FileUploader({
            url: $attrs.uploadUrl,
            filters: [{
                name: 'filesize',
                fn: function(item) {
                    if (item.size > $attrs.maxFileSize) {
                        $scope.errorFiles.push(item);

                        return false;
                    }

                    return true;
                }
            }]
        });

        $scope.uploadCategoryChange = function() {
            $('#categorySearchSelect').val($scope.category);
            angular.element($('#categorySearchSelect')[0]).triggerHandler('input');
        }

        uploader.onBeforeUploadItem = function onBeforeUploadItem(item) {
            item.formData = [{
                category: $scope.category
            }];
        }


        uploader.onCompleteAll = function() {
            requestFromServer(url, currentTableState);
            $scope.errorFiles = [];
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

        $scope.getSrc = function(media, dimension) {
            return MediaService.getMediaUrl(media.directory, media.filename, media.mimeType, dimension);
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
