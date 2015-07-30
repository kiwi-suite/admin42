angular.module('admin42')
    .controller('TagsElementController', ['$scope', '$attrs', '$http', '$q', function ($scope, $attrs, $http, $q) {
        var currentTableState = {};
        var url = $attrs.url;

        var canceler = null;

        $scope.tags = {
            tags: [],
            //selectedTags: [{"id": 1, "tag": "foobar"},{"id": 2, "tag": "test"}]
            selectedTags: [],
            fieldValue: ''
        };

        $scope.addTagTransform = function(tag) {
             return {
                "id": 0,
                "tag": tag
             };
        };

        $scope.updateHiddenField = function() {
            var fieldValue = '';
            angular.forEach($scope.tags.selectedTags, function(value, key) {
                fieldValue += value.tag + ',';
            });
            $scope.tags.fieldValue = fieldValue.substring(0, fieldValue.length - 1);
        };

        $scope.refreshTags = function(tag) {

            $scope.tags.tags = [];

            var params = {tag: tag};
            console.log('callback ' + tag);

            if (tag.length > 0) {

                if (canceler != null) {
                    console.log('cancel...');
                    //console.log($scope.canceler);
                    canceler.resolve();
                }

                canceler = $q.defer();

                var request = $http({
                    method: 'get',
                    url: url,
                    params: params,
                    timeout: canceler.promise
                });
                request.then(function(response) {
                    $scope.canceler = null;
                    $scope.tags.tags = response.data.results
                });

            }
        };
}]);
