angular.module('admin42', [
    'ui.bootstrap',
    'ui.bootstrap.datetimepicker',
    'ngAnimate',
    'ngSanitize',
    'ui.utils',
    'smart-table',
    'toaster',
    'ngStorage',
    'ui.sortable',
    'ui.select',
    'angularFileUpload'
]);

angular.module('admin42').config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}]);
;angular.module('admin42')
    .directive('adminDynamicForm', [function() {
        return {
            restrict: 'A',
            scope: {
                templateName: '@',
                adminDynamicFormElements: '@',
                adminDynamicPrototypes: '@'
            },
            templateUrl: 'dynamic_form/base.html',
            controller: ['$scope', function($scope) {
                $scope.data = {};
                $scope.elements = angular.fromJson($scope.adminDynamicFormElements);
                $scope.prototypes = angular.fromJson($scope.adminDynamicPrototypes);

                $scope.data.selectedPrototype = $scope.prototypes[0];

                $scope.addTemplate = function() {
                    $scope.elements.push($scope.data.selectedPrototype);
                };

                $scope.sortableOptions = {
                    axis: 'y',
                    opacity: 0.5,
                    handle: '.panel-sort-handle',
                    items: "> .sortable-container",
                    placeholder: "sortable-placeholder"
                };

                $scope.getName = function(element, name, index) {
                    if (element.initial === false) {
                        return element.name + '[' + index +'][' + name + ']';
                    }
                    return element.name + '[' + name + ']';
                }
            }]
        };
    }]);
;angular.module('admin42')
    .directive('uiFullscreen', ['$document', function($document) {
        return {
            restrict: 'AC',
            template: '<i class="fa fa-expand fa-fw text"></i><i class="fa fa-compress fa-fw text-active"></i>',
            link: function (scope, el, attr) {
                el.addClass('hide');
                if (screenfull.enabled && !navigator.userAgent.match(/Trident.*rv:11\./)) {
                    el.removeClass('hide');
                }
                el.on('click', function () {
                    var target;
                    attr.target && ( target = $(attr.target)[0] );
                    screenfull.toggle(target);
                });
                $document.on(screenfull.raw.fullscreenchange, function () {
                    if (screenfull.isFullscreen) {
                        el.addClass('active');
                    } else {
                        el.removeClass('active');
                    }
                });
            }
        };
    }]);
;angular.module('admin42')
    .factory('jsonCache', ['$cacheFactory', function($cacheFactory) {
        return $cacheFactory('json-cache');
    }])
    .directive('script', ['jsonCache', function(jsonCache) {
        return {
            restrict: 'E',
            terminal: true,
            compile: function(element, attr) {
                if (attr.type == 'application/json') {
                    var jsonHandler = attr.id,
                        json = angular.fromJson(element[0].text);
                    jsonCache.put(jsonHandler, json);
                }
            }
        };
    }]);
;angular.module('admin42')
    .directive('uiNav', [function() {
        return {
            restrict: 'AC',
            link: function(scope, el, attr) {
                var _window = $(window),
                    _mb = 768,
                    wrap = $('.app-aside'),
                    next,
                    backdrop = '.dropdown-backdrop';
                // unfolded
                el.on('click', 'a', function(e) {
                    next && next.trigger('mouseleave.nav');
                    var _this = $(this);
                    _this.parent().siblings( ".active" ).toggleClass('active');
                    _this.next().is('ul') &&  _this.parent().toggleClass('active') &&  e.preventDefault();
                    // mobile
                    _this.next().is('ul') || ( ( _window.width() < _mb ) && $('.app-aside').removeClass('show off-screen') );
                });

                // folded & fixed
                el.on('mouseenter', 'a', function(e){
                    next && next.trigger('mouseleave.nav');
                    $('> .nav', wrap).remove();
                    if ( !$('.app-aside-fixed.app-aside-folded').length || ( _window.width() < _mb ) || $('.app-aside-dock').length) return;
                    var _this = $(e.target)
                        , top
                        , w_h = $(window).height()
                        , offset = 50
                        , min = 150;

                    !_this.is('a') && (_this = _this.closest('a'));
                    if( _this.next().is('ul') ){
                        next = _this.next();
                    }else{
                        return;
                    }

                    _this.parent().addClass('active');
                    top = _this.parent().position().top + offset;
                    next.css('top', top);
                    if( top + next.height() > w_h ){
                        next.css('bottom', 0);
                    }
                    if(top + min > w_h){
                        next.css('bottom', w_h - top - offset).css('top', 'auto');
                    }
                    next.appendTo(wrap);

                    next.on('mouseleave.nav', function(e){
                        $(backdrop).remove();
                        next.appendTo(_this.parent());
                        next.off('mouseleave.nav').css('top', 'auto').css('bottom', 'auto');
                        _this.parent().removeClass('active');
                    });

                    $('.smart').length && $('<div class="dropdown-backdrop"/>').insertAfter('.app-aside').on('click', function(next){
                        next && next.trigger('mouseleave.nav');
                    });

                });

                wrap.on('mouseleave', function(e){
                    next && next.trigger('mouseleave.nav');
                    $('> .nav', wrap).remove();
                });
            }
        };
    }]);
;angular.module('admin42')
    .directive('ngInitial', function() {
        return{
            restrict: 'A',
            controller: ['$scope', '$element', '$attrs', '$parse', function($scope, $element, $attrs, $parse){

                var getter, setter, val, tag;
                tag = $element[0].tagName.toLowerCase();

                val = $attrs.initialValue || $element.val();
                if(tag === 'input'){
                    if($element.attr('type') === 'checkbox'){
                        val = $element[0].checked ? true : undefined;
                    } else if($element.attr('type') === 'radio'){
                        val = ($element[0].checked || $element.attr('selected') !== undefined) ? $element.val() : undefined;
                    }
                }

                if($attrs.ngModel){
                    getter = $parse($attrs.ngModel);
                    setter = getter.assign;
                    setter($scope, val);
                }
            }]
        };
    });
;/**
 * modified stSearch directive for smart-tables
 * remove as soon as fixes are available from original vendor
 * fixes: multiple preset search input values
 */

angular.module('smart-table')
    .directive('stSearch42', ['stConfig', '$timeout', function (stConfig, $timeout) {
        return {
            require: '^stTable',
            link: function (scope, element, attr, ctrl) {
                var tableCtrl = ctrl;
                var promise = null;
                var throttle = attr.stDelay || stConfig.search.delay;

                attr.$observe('stSearch42', function (newValue, oldValue) {
                    var input = element[0].value;
                    if (newValue !== oldValue && input) {
                        //ctrl.tableState().search = {}; // TODO: @lcs: this prevents multiple preset values from working
                        tableCtrl.search(input, newValue);
                    }
                });

                //table state -> view
                scope.$watch(function () {
                    return ctrl.tableState().search;
                }, function (newValue, oldValue) {
                    var predicateExpression = attr.stSearch42 || '$';
                    if (newValue.predicateObject && newValue.predicateObject[predicateExpression] !== element[0].value) {
                        element[0].value = newValue.predicateObject[predicateExpression] || '';
                    }
                }, true);

                // view -> table state
                element.bind('input', function (evt) {
                    evt = evt.originalEvent || evt;
                    if (promise !== null) {
                        $timeout.cancel(promise);
                    }

                    promise = $timeout(function () {
                        tableCtrl.search(evt.target.value, attr.stSearch42 || '');
                        promise = null;
                    }, throttle);
                });
            }
        };
    }]);
;angular.module('admin42')
    .filter('datetime', function(appConfig) {
        return function(input, emptyValue, format, timezone) {
            if (angular.isUndefined(emptyValue)) {
                emptyValue = "-";
            }
            if (angular.isUndefined(format)) {
                format = appConfig.defaultDateTimeFormat;
            }
            if (angular.isUndefined(timezone)) {
                timezone = appConfig.timezone;
            }
            var dateTime;
            if (angular.isObject(input) && input.constructor.name == 'Date') {
                dateTime = moment.tz(input, input.timezone);
            } else if (angular.isObject(input)) {
                if (angular.isUndefined(input.date)) {
                    return emptyValue;
                }
                dateTime = moment.tz(input.date, input.timezone);
            } else if (angular.isString(input)) {
                if (input.length == 0) {
                    return emptyValue;
                }
                dateTime = moment.tz(input, timezone);
            } else {
                return emptyValue;
            }

            dateTime.locale(appConfig.locale);
            return dateTime.format(format);
        };
    });
;angular.module('admin42').controller('AppController',['$scope', 'toaster', '$timeout', '$localStorage', function($scope, toaster, $timeout, $localStorage){
    $scope.app = {
        $storage: $localStorage.$default({
            asideFolded: false
        })
    };

    $scope.app.appContentFull = false;

    $timeout(function(){
        angular.forEach(FLASH_MESSAGE, function(messages, namespace){
            if (messages.length == 0) {
                return;
            }

            angular.forEach(messages, function(message){
                toaster.pop(namespace, message.title, message.message);
            });
        });
    }, 1000);

}]);
;angular.module('admin42')
    .controller('CropController', ['$scope', '$http', '$timeout', 'Cropper', 'jsonCache', '$attrs', '$interval', function ($scope, $http, $timeout, Cropper, jsonCache, $attrs, $interval) {
        $scope.data = [];

        $scope.dimensions = jsonCache.get($attrs.json)['dimension'];
        $scope.meta = jsonCache.get($attrs.json)['meta'];
        $scope.selectedHandle = $attrs.mediaSelector;

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

        $scope.saveCroppedImage = function(handle, url) {
            if (angular.isUndefined($scope.data[handle])) {
                return false;
            }

            url = url.replace('{{ name }}', handle);

            $http.post(url, $scope.data[handle]);
        };

        $scope.selectDimension = function(handle) {
            $scope.selectedHandle = handle;

            Cropper.getJqueryCrop().cropper("destroy");

            var dimension = $scope.dimensions[handle];

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
                        console.log($scope.meta[handle]);
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
;angular.module('admin42').controller('DataGridController',['$scope', '$http', '$attrs', function($scope, $http, $attrs){
    var url = $attrs.url;

    $scope.collection = [];
    $scope.isLoading = true;
    $scope.displayedPages = 1;

    $scope.callServer = function (tableState) {
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
    };
}]);
;angular.module('admin42')
    .controller('AdminDatepickerController',['$scope', '$attrs', function($scope, $attrs){
        $scope.opened = false;

        $scope.open = function($event) {
            $event.preventDefault();
            $event.stopPropagation();

            $scope.opened = true;
        };

        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1,
            enableDate: true,
            enableTime: true,
            class: 'datepicker',
            showWeeks: false,
            timeText: 'Time',
            startingDay: 1
        };

        console.log($scope);
}]);
;angular.module('admin42')
    .controller('FileSelectorController', ['$scope', '$attrs', function ($scope, $attrs) {

}]);
;angular.module('admin42')
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
;angular.module('admin42').controller('ModalController', ['$scope', '$modalInstance', function ($scope, $modalInstance) {
    $scope.ok = function () {
        $modalInstance.close();
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
}]);
;angular.module('admin42').controller('NotificationController', ['$scope', '$interval', '$http', '$attrs', function($scope, $interval, $http, $attrs){
        var notificationUrl = $attrs.notificationUrl;
        var updateNotifications = function() {
            $http.get(notificationUrl).
                success(function(data) {
                    $scope.notifications = data;
                }).
                error(function() {
                    $scope.notifications = [];
                });
        };

        $scope.notifications = [];
        $interval(updateNotifications, 30000);
        updateNotifications();

        $scope.clearNotifications = function(clearUrl) {
            $http.post(clearUrl).
                success(function() {
                    $scope.notifications = [];
                }).
                error(function() {});
        };
    }]);
;angular.module('admin42')
    .controller('SelectController', ['$scope', '$attrs', 'jsonCache', function($scope, $attrs, jsonCache){
        $scope.items = jsonCache.get($attrs.jsonDataId);
        $scope.item = {};

        if ($attrs.initValue.length > 0) {
            angular.forEach($scope.items, function(value){
                if (value.id == $attrs.initValue) {
                    $scope.item.selected = value;
                }
            });
        }
    }]);
