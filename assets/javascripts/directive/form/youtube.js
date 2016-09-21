angular.module('admin42')
    .directive('formYoutube', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/youtube.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', '$sce', function($scope, jsonCache, $sce) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);
                $scope.youtubeLink = "";

                if ($scope.formData.value.length > 0) {
                    $scope.youtubeLink = "https://www.youtube.com/watch?v=" + $scope.formData.value;
                }
                $scope.changeValue = function() {
                    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                    var match = $scope.youtubeLink.match(regExp);
                    if (match && match[2].length == 11) {
                        $scope.formData.value = match[2];
                        $scope.youtubeLink = "https://www.youtube.com/watch?v=" + $scope.formData.value;
                    } else {
                        $scope.formData.value = "";
                    }
                }

                $scope.videoUrl = function() {
                    return $sce.trustAsResourceUrl("https://www.youtube.com/embed/" + $scope.formData.value);
                }
            }]
        }
    }]);
