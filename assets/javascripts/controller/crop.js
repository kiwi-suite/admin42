angular.module('admin42')
    .controller('CropController', ['$scope', '$http', '$timeout', 'Cropper', 'jsonCache', '$attrs', '$interval', function ($scope, $http, $timeout, Cropper, jsonCache, $attrs, $interval) {
        $scope.data = [];

        $scope.dimensions = jsonCache.get($attrs.json)['dimension'];
        $scope.meta = jsonCache.get($attrs.json)['meta'];
        $scope.selectedHandle = null;

        var imageSize = jsonCache.get($attrs.json)['imageSize'];

        $scope.isActive = function(handle) {
            if (handle == $scope.selectedHandle) {
                return 'active';
            }

            return '';
        };

        $scope.hasChanges = function(handle) {
            if (angular.isUndefined($scope.data[handle])) {
                return false;
            }

            return true;
        };

        $scope.checkImageSize = function(currentDimension){
            if (imageSize.width < currentDimension.width || imageSize.height < currentDimension.height) {
                return false;
            }

            return true;
        };

        angular.forEach($scope.dimensions, function(value, key) {
            if (this.selectedHandle !== null) {
                return;
            }

            if (!this.checkImageSize(key)) {
                return;
            }

            this.selectedHandle = key;
        }, $scope);

        $scope.saveCroppedImage = function(handle, url) {
            if (angular.isUndefined($scope.data[handle])) {
                return false;
            }

            url = url.replace('{{ name }}', handle);

            $http.post(url, $scope.data[handle]);
        };

        $scope.selectDimension = function(handle) {
            var dimension = $scope.dimensions[handle];

            Cropper.getJqueryCrop().cropper("destroy");

            $scope.selectedHandle = handle;

            var options = {
                crop: function(dataNew) {
                    $scope.data[$scope.selectedHandle] = dataNew;
                },
                responsive: true,
                autoCrop: false,
                rotatable: false,
                zoomable: false,
                guides: false,
                built: function(e) {
                    if (!angular.isUndefined($scope.meta[handle])) {
                        $(this).cropper('setCropBoxData', {
                            x:0,
                            y:0,
                            width: 500,
                            height: 500
                        });
                    } else {
                        $(this).cropper('setCropBoxData', {
                            x:0,
                            y:0,
                            width: dimension.width,
                            height: dimension.height
                        });
                    }
                    $(this).cropper('crop');
                }
            }

            if (dimension.width != 'auto' && dimension.height != 'auto') {
                options.aspectRatio = dimension.width / dimension.height;
            }

            Cropper.getJqueryCrop().on('dragmove.cropper', function (e) {
                var $cropper = $(e.target);

                var data = $cropper.cropper('getCropBoxData');
                var imageData = $cropper.cropper('getImageData');

                if (dimension.width != 'auto' && data.width < dimension.width / (imageData.naturalWidth/imageData.width)) {
                    return false;
                }

                if (dimension.height != 'auto' && data.height < dimension.height / (imageData.naturalHeight/imageData.height)) {
                    return false;
                }

                return true;
            }).on('dragstart.cropper', function (e) {
                var $cropper = $(e.target);

                var data = $cropper.cropper('getCropBoxData');
                var imageData = $cropper.cropper('getImageData');
                var hasChanged = false;

                if (dimension.width != 'auto') {
                    var width = dimension.width / (imageData.naturalWidth/imageData.width);
                    if (angular.isUndefined(data.width) || data.width < width) {
                        data.width = width;
                        hasChanged = true;
                    }
                }

                if (dimension.height != 'auto') {
                    var height = dimension.height / (imageData.naturalHeight/imageData.height);
                    if (angular.isUndefined(data.height) || data.height < height) {
                        data.height = height;
                        hasChanged = true;
                    }

                }

                if (hasChanged) {
                    $(e.target).cropper('setCropBoxData', data);
                }
            });

            Cropper.getJqueryCrop().cropper(options);
        };

        var stop = $interval(function() {
            if (Cropper.getJqueryCrop() != null) {
                $scope.selectDimension($scope.selectedHandle);
                stopInterval();
            }
        }, 100);
        function stopInterval() {
            $interval.cancel(stop);
        }
    }]);

angular.module('admin42')
    .directive('ngCropper', ['Cropper', function(Cropper) {
        return {
            restrict: 'A',
            link: function(scope, element, atts) {
                Cropper.setJqueryCrop(element);
            }
        };
    }])
.service('Cropper', [function() {
    this.crop = null;

    this.setJqueryCrop = function(crop) {
        this.crop = crop;
    };
    this.getJqueryCrop = function() {
        return this.crop;
    };
}]);
