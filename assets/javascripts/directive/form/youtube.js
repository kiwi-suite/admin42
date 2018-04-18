angular.module('admin42')
    .directive('formYoutube', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$sce', '$formService', function($scope, jsonCache, $sce, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);
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

                    $scope.formData.errors = [];
                };

                $scope.videoUrl = function() {
                    return $sce.trustAsResourceUrl("https://www.youtube.com/embed/" + $scope.formData.value);
                };

                $scope.empty = function() {
                    $scope.youtubeLink = "";
                    $scope.changeValue();
                };

                $scope.preventEnter = function($event) {
                    if ($event.keyCode != 13) {
                        return;
                    }
                    $event.preventDefault();
                };

                if (angular.isDefined($scope.formData.options.formServiceHash)) {
                    $formService.put(
                        $scope.formData.options.formServiceHash,
                        $scope.formData.name,
                        $scope.elementDataId
                    );
                }
            }]
        }
    }]);
