angular.module('admin42')
    .directive('youtube', [function () {
        return {
            restrict: 'E',
            scope: true,
            transclude: false,
            controller: ['$scope', '$sce', '$attrs',  function($scope, $sce, $attrs) {
                $scope.youtubeCode = $attrs.youtubeCode;
                if ($scope.youtubeCode.length > 0) {
                    $scope.youtubeLink = "https://www.youtube.com/?v=" + $scope.youtubeCode;
                }
                $scope.changeValue = function() {
                    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                    var match = $scope.youtubeLink.match(regExp);
                    if (match && match[2].length == 11) {
                        $scope.youtubeCode = match[2];
                        $scope.youtubeLink = "https://www.youtube.com/?v=" + $scope.youtubeCode;
                    } else {
                        $scope.youtubeCode = "";
                    }
                }

                $scope.videoUrl = function() {
                    return $sce.trustAsResourceUrl("https://www.youtube.com/embed/" + $scope.youtubeCode);
                }
            }]
        }
    }]
);
