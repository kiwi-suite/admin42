angular.module('admin42')
    .controller('LinkController',['$scope', '$attrs', '$modal', 'jsonCache', function($scope, $attrs, $modal, jsonCache){
        $scope.linkId = jsonCache.get($attrs.jsonDataId)['linkId'];
        $scope.linkType = jsonCache.get($attrs.jsonDataId)['linkType'];
        $scope.linkValue = jsonCache.get($attrs.jsonDataId)['linkValue'];
        $scope.linkDisplayName = jsonCache.get($attrs.jsonDataId)['linkDisplayName'];

        $scope.selectLink = function() {
            var modalInstance = $modal.open({
                animation: true,
                templateUrl: 'linkModalSelectorContent.html',
                controller: 'LinkModalSelectorController',
                size: 'lg',
                resolve: {
                    linkSaveUrl: function () {
                        return $attrs.linkSaveUrl;
                    },
                    linkType: function () {
                        return $scope.linkType;
                    },
                    linkValue: function () {
                        return $scope.linkValue;
                    },
                    linkId: function () {
                        return $scope.linkId;
                    }
                }
            });

            modalInstance.result.then(function(data) {
                $scope.linkId = data.linkId;
                $scope.linkDisplayName = data.linkDisplayName;
                $scope.linkValue = data.linkValue;
                $scope.linkType = data.linkType;
            }, function () {

            });
        };

        $scope.clearLink = function() {
            $scope.linkId = "";
            $scope.linkDisplayName = "";
        }
    }]
);

angular.module('admin42')
    .controller('LinkModalSelectorController', ['$scope', '$modalInstance', '$http', 'linkSaveUrl', 'linkType', 'linkValue', 'linkId', function ($scope, $modalInstance, $http, linkSaveUrl, linkType, linkValue, linkId) {
        $scope.includeArray = [];

        $scope.link = {
            setValue: function(value) {
                linkValue = value;
            },
            getValue: function(){
                return linkValue;
            }
        };

        $scope.change = function(){
            if ($scope.linkType.length > 0) {
                $scope.includeArray = ['/link/' + $scope.linkType + '.html'];

                return;
            }

            $scope.includeArray = [];
        };

        if (linkType !== null) {
            $scope.linkType = linkType;
            $scope.change();
        }

        $scope.ok = function () {
            if (linkValue == null || $scope.linkType.length == 0) {
                $modalInstance.close({
                    linkId: null,
                    linkDisplayName: null,
                    linkValue: null,
                    linkType: null
                });

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
                    $modalInstance.close({
                        linkId: data.linkId,
                        linkDisplayName: data.linkDisplayName,
                        linkValue: linkValue,
                        linkType: $scope.linkType
                    });
                })
                .error(function (){
                    $modalInstance.dismiss('cancel');
                });

        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    }]);

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

angular.module('admin42')
    .controller('LinkMediaController',['$scope', function($scope){
        $scope.selectedMedia = null;

        var inititalValue = $scope.link.getValue();
        if (inititalValue != null) {
            $scope.selectedMedia = inititalValue.id
        }

        $scope.selectMedia = function(media) {
            if ($scope.selectedMedia == media.id) {
                $scope.selectedMedia = null;
                selectedMedia = null;

                return;
            }
            $scope.selectedMedia = media.id;

            $scope.link.setValue({
                id: media.id,
            });
        };
    }]
);