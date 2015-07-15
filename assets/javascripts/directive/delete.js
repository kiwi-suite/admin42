angular.module('admin42')
    .directive('delete', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/delete.html',
            scope: {
                callback: "="
            },
            controller: ['$scope', '$attrs', '$modal', '$window', '$parse', function($scope, $attrs, $modal, $window, $parse) {
                if (angular.isUndefined($attrs.size)) {
                    $scope.size = "lg";
                } else {
                    if ($attrs.size != "lg" && $attrs.size != "sm" && $attrs.size != "xs") {
                        $scope.size = "lg";
                    } else {
                        $scope.size = $attrs.size;
                    }
                }

                if (angular.isUndefined($attrs.method)) {
                    $scope.method = "post";
                } else {
                    if ($attrs.method != "post" && $attrs.method != "delete") {
                        $scope.method = "post";
                    } else {
                        $scope.method = $attrs.method;
                    }
                }

                $scope.delete = function() {
                    var modalInstance = $modal.open({
                        animation: true,
                        templateUrl: 'element/delete-modal.html',
                        controller: 'DeleteModalController',
                        resolve: {
                            requestUrl: function(){
                                return $attrs.url;
                            },
                            requestParams: function(){
                                var params = {};

                                angular.forEach($attrs, function(value, key) {
                                    if (key.substring(0, 7) != "request") return;

                                    this[key.substring(7).toLowerCase()] = value;
                                }, params);

                                return params;
                            },
                            requestTitle: function(){
                                return $attrs.title;
                            },
                            requestContent: function(){
                                return $attrs.content;
                            },
                            requestMethod: function(){
                                return $scope.method;
                            }
                        }
                    });

                    modalInstance.result.then(function (data) {
                        if (angular.isDefined(data.redirect)) {
                            $window.location.href = data.redirect;

                            return;
                        }

                        if (angular.isDefined($scope.callback)) {
                            $scope.callback();
                        }
                    }, function () {

                    });
                }
            }]
        };
    }]);

angular.module('admin42')
    .controller('DeleteModalController', [
            '$scope',
            '$modalInstance',
            '$http',
            'requestUrl',
            'requestParams',
            'requestTitle',
            'requestContent',
            'requestMethod',
            function ($scope, $modalInstance, $http, requestUrl, requestParams, requestTitle, requestContent, requestMethod) {
                $scope.title = requestTitle;
                $scope.content = requestContent;

                $scope.ok = function () {
                    $http({
                        method: requestMethod.toUpperCase(),
                        url: requestUrl,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        data: requestParams,
                        transformRequest: function(obj) {
                            var str = [];
                            for(var p in obj)
                                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                            return str.join("&");
                        }
                    })
                        .success(function (data){
                            $modalInstance.close(data);
                        })
                        .error(function (){
                            $modalInstance.dismiss('cancel');
                        });
                };

                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
    }]);
