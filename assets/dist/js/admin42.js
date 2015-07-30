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
;angular.module('admin42')
    .directive('adminDynamicForm', [function() {
        return {
            restrict: 'A',
            scope: {
                templateName: '@',
                adminDynamicFormElements: '@',
                adminDynamicPrototypes: '@'
            },
            templateUrl: function(elem,attrs){
                return attrs.baseForm;
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.data = {};
                $scope.elements = jsonCache.get($scope.adminDynamicFormElements);
                $scope.prototypes = jsonCache.get($scope.adminDynamicPrototypes);

                $scope.data.selectedPrototype = $scope.prototypes[0];

                $scope.addTemplate = function() {
                    var element = angular.copy($scope.data.selectedPrototype);
                    element.internIndex = $scope.elements.length;
                    $scope.elements.push(element);
                };

                $scope.sortableOptions = {
                    axis: 'y',
                    opacity: 0.5,
                    handle: '.panel-sort-handle',
                    items: "> .sortable-container",
                    placeholder: "sortable-placeholder",
                    start: function() {
                        $scope.$broadcast('$tinyWysiwyg:disable');
                        $scope.$emit('$tinyWysiwyg:disable');
                    },
                    stop: function(event, ui) {
                        $scope.$broadcast('$tinyWysiwyg:enable');
                        $scope.$emit('$tinyWysiwyg:enable');
                    }
                };

            }]
        };
    }]);
;angular.module('admin42').directive('dynamicModel', ['$compile', function ($compile) {
    return {
        'link': function(scope, element, attrs) {
            scope.$watch(attrs.dynamicModel, function(dynamicModel) {
                if (attrs.ngModel == dynamicModel || !dynamicModel) return;

                element.attr('ng-model', dynamicModel);
                if (dynamicModel == '') {
                    element.removeAttr('ng-model');
                }

                // Unbind all previous event handlers, this is 
                // necessary to remove previously linked models.
                element.unbind();
                $compile(element)(scope);
            });
        }
    };
}]);;angular.module('admin42')
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
  .directive('uiToggleClass', ['$timeout', '$document', function($timeout, $document) {
    return {
      restrict: 'AC',
      link: function(scope, el, attr) {
        el.on('click', function(e) {
          e.preventDefault();
          var classes = attr.uiToggleClass.split(','),
              targets = (attr.target && attr.target.split(',')) || Array(el),
              key = 0;
          angular.forEach(classes, function( _class ) {
            var target = targets[(targets.length && key)];            
            ( _class.indexOf( '*' ) !== -1 ) && magic(_class, target);
            $( target ).toggleClass(_class);
            key ++;
          });
          $(el).toggleClass('active');

          function magic(_class, target){
            var patt = new RegExp( '\\s' + 
                _class.
                  replace( /\*/g, '[A-Za-z0-9-_]+' ).
                  split( ' ' ).
                  join( '\\s|\\s' ) + 
                '\\s', 'g' );
            var cn = ' ' + $(target)[0].className + ' ';
            while ( patt.test( cn ) ) {
              cn = cn.replace( patt, ' ' );
            }
            $(target)[0].className = $.trim( cn );
          }
        });
      }
    };
  }]);;angular.module('admin42')
    .directive('tinyWysiwyg', ['$timeout', '$window', function ($timeout, $window) {
        var editorCounter = 0;
        var idPrefix = 'tiny-wysiwyg';

        return {
            link: function(scope, element, attrs, ctrls) {
                var tinyInstance;

                attrs.$set('id', idPrefix + '-' + editorCounter++);

                var expression = {};
                angular.extend(expression, scope.$eval(attrs.tinyWysiwyg));
                var options = {
                    format: 'raw',
                    selector: '#' + attrs.id
                };
                angular.extend(options, expression);

                $timeout(function() {
                    tinymce.init(options);
                    tinyInstance = tinymce.get(attrs.id);
                });

                scope.$on('$tinyWysiwyg:disable', function() {
                    tinymce.execCommand('mceRemoveEditor', false, attrs.id);
                });

                scope.$on('$tinyWysiwyg:enable', function() {
                    tinymce.execCommand('mceAddEditor', false, attrs.id);
                });
            }
        }
    }]);
;angular.module('admin42')
    .directive('youtube', [function () {
        return {
            restrict: 'E',
            scope: true,
            transclude: false,
            controller: ['$scope', '$sce', '$attrs',  function($scope, $sce, $attrs) {
                $scope.youtubeCode = $attrs.youtubeCode;
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
;angular.module('admin42')
    .filter('bytes', function() {
        return function(bytes) {
            if (isNaN(parseFloat(bytes)) || !isFinite(bytes) || bytes == 0) return '0';
            var units = {1: 'KB', 2: 'MB', 3: 'GB', 4: 'TB'},
                measure, floor, precision;
            if (bytes > 1099511627775) {
                measure = 4;
            } else if (bytes > 1048575999 && bytes <= 1099511627775) {
                measure = 3;
            } else if (bytes > 1024000 && bytes <= 1048575999) {
                measure = 2;
            } else if (bytes <= 1024000) {
                measure = 1;
            }
            floor = Math.floor(bytes / Math.pow(1024, measure)).toString().length;
            if (floor > 3) {
                precision = 0
            } else {
                precision = 3 - floor;
            }
            return (bytes / Math.pow(1024, measure)).toFixed(precision) + ' ' + units[measure];
        }
    });;angular.module('admin42')
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

    if(typeof FLASH_MESSAGE !== 'undefined') {
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
    }

}]);
;angular.module('admin42')
    .controller('CropController', ['$scope', '$http', '$timeout', 'Cropper', 'jsonCache', '$attrs', '$interval', function ($scope, $http, $timeout, Cropper, jsonCache, $attrs, $interval) {
        $scope.data = [];

        $scope.dimensions = jsonCache.get($attrs.json)['dimension'];
        $scope.meta = jsonCache.get($attrs.json)['meta'];
        $scope.selectedHandle = null;

        var imageSize = jsonCache.get($attrs.json)['imageSize'];

        $scope.hasChanges = {};

        $scope.currentInfo = {
            x: 0,
            y: 0,
            width: 0,
            height: 0,
            rotate: 0,
            calcWidth: 0,
            calcHeight: 0
        };

        $scope.isActive = function(handle) {
            if (handle == $scope.selectedHandle) {
                return 'active';
            }

            return '';
        };

        $scope.checkChanges = function(handle) {
            if (angular.isUndefined($scope.data[handle])) {
                return false;
            }
            if (angular.isUndefined($scope.meta[handle])) {
                return true;
            }
            if ($scope.data[handle].x == $scope.meta[handle].x &&
                $scope.data[handle].y == $scope.meta[handle].y &&
                $scope.data[handle].width == $scope.meta[handle].width &&
                $scope.data[handle].height == $scope.meta[handle].height
            ) {
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

            if (!this.checkImageSize(value)) {
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

        function setCurrentInfo(currentInfo) {
            var dimension = $scope.dimensions[$scope.selectedHandle];

            if (dimension.width != "auto" && dimension.height != "auto") {
                currentInfo.calcWidth = dimension.width;
                currentInfo.calcHeight = dimension.height;
            } else if(dimension.width == "auto" && dimension.height == "auto") {
                currentInfo.calcWidth = currentInfo.width;
                currentInfo.calcHeight = currentInfo.height;
            } else if (dimension.width == "auto" && dimension.height != "auto") {
                var ratio = currentInfo.height/dimension.height;

                currentInfo.calcWidth = Math.round(currentInfo.width/ratio);
                currentInfo.calcHeight = dimension.height;
            } else {
                var ratio = currentInfo.width/dimension.width;

                currentInfo.calcWidth = dimension.width;
                currentInfo.calcHeight = Math.round(currentInfo.height / ratio);
            }
            $scope.currentInfo = currentInfo;
            $scope.$apply();
        }

        $scope.selectDimension = function(handle) {
            $scope.currentInfo = {
                x: 0,
                y: 0,
                width: 0,
                height: 0,
                rotate: 0,
                calcWidth: 0,
                calcHeight: 0
            };
            var dimension = $scope.dimensions[handle];

            Cropper.getJqueryCrop().cropper("destroy");

            $scope.selectedHandle = handle;

            var options = {
                crop: function(dataNew) {
                    $scope.data[$scope.selectedHandle] = dataNew;
                },

                strict: true,
                zoomable: false,
                responsive: true,
                rotatable: false,
                guides: true
            };

            if (!angular.isUndefined($scope.data[handle])) {
                options.data = $scope.data[handle];
            } else if (!angular.isUndefined($scope.meta[handle])) {
                options.data = {"x": $scope.meta[handle].x, "y": $scope.meta[handle].y, "width":$scope.meta[handle].width, "height":$scope.meta[handle].height, "rotate":0};
            } else {
                options.data = { "width": dimension.width, "height": dimension.height,  "rotate":0};
                options.built = function(e) {
                    var data = $(this).cropper('getData');
                    var imageData = $(this).cropper('getImageData');

                    var x = (imageData.naturalWidth - data.width) / 2;
                    var y = (imageData.naturalHeight - data.height) / 2;

                    $(this).cropper("setData", {"x": x, "y": y});
                }
            }

            if (dimension.width != 'auto' && dimension.height != 'auto') {
                options.aspectRatio = dimension.width / dimension.height;
            }

            Cropper.getJqueryCrop().off('dragmove.cropper');
            Cropper.getJqueryCrop().off('dragstart.cropper');

            Cropper.getJqueryCrop().cropper(options);

            Cropper.getJqueryCrop().on('dragmove.cropper', function (e) {
                var $cropper = $(e.target);

                var data = $cropper.cropper('getCropBoxData');
                var imageData = $cropper.cropper('getImageData');

                setCurrentInfo($cropper.cropper('getData', true));

                if (dimension.width != 'auto' && data.width < dimension.width / (imageData.naturalWidth/imageData.width)) {
                    return false;
                }

                if (dimension.height != 'auto' && data.height < dimension. height / (imageData.naturalHeight/imageData.height)) {
                    return false;
                }

                return true;
            }).on('dragstart.cropper', function (e) {
                var $cropper = $(e.target);

                var data = $cropper.cropper('getCropBoxData');
                var imageData = $cropper.cropper('getImageData');
                var hasChanged = false;

                setCurrentInfo($cropper.cropper('getData', true));

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

                $scope.hasChanges[handle] = true;
                $scope.$apply();

                if (hasChanged) {
                    $(e.target).cropper('setCropBoxData', data);
                }
            }).on('built.cropper', function (e) {
                var $cropper = $(e.target);
                setCurrentInfo($cropper.cropper('getData', true));
            });
        };

        var stop = $interval(function() {
            if (Cropper.getJqueryCrop() != null && $scope.selectedHandle != null) {
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
}]);
;angular.module('admin42')
    .controller('FileSelectorController', ['$scope', '$attrs', 'jsonCache', '$modal', 'MediaService', function ($scope, $attrs, jsonCache, $modal, MediaService) {
        $scope.media = jsonCache.get($attrs.jsonDataId);

        $scope.tabs = {
            media: {
                active: $attrs.ngType !== 'file',
                disabled: false
            },
            sitemap: {
                active: $attrs.ngType === 'file',
                disabled: $attrs.ngType !== 'file'
            }
        };

        $scope.clearMedia = function() {
            $scope.media = [];
        }

        $scope.isImage = function() {
            if (angular.isUndefined($scope.media.mimeType)) {
                return false;
            }
            return ($scope.media.mimeType.substr(0, 6) == "image/");
        };

        $scope.getSrc = function(media, dimension) {
            return MediaService.getMediaUrl(media.directory, media.filename, media.mimeType, dimension);
        };

        $scope.selectMedia = function() {
            var modalInstance = $modal.open({
                animation: true,
                templateUrl: $attrs.modalTemplate,
                controller: 'MediaModalSelectorController',
                size: 'lg'
            });

            modalInstance.result.then(function(media) {
                if (media !== null) {
                    $scope.media = media;
                }
            }, function () {

            });
        };
}]);

angular.module('admin42')
    .controller('MediaModalSelectorController', ['$scope', '$modalInstance', function ($scope, $modalInstance) {
        var selectedMedia = null;


        $scope.selectMedia = function(media) {
            if ($scope.selectedMedia == media.id) {
                $scope.selectedMedia = null;
                selectedMedia = null;

                return;
            }
            $scope.selectedMedia = media.id;
            selectedMedia = media;
        };

        $scope.ok = function () {
            $modalInstance.close(selectedMedia);
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    }]);
;angular.module('admin42')
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
);;angular.module('admin42')
    .controller('LinkDialogController', ['$scope', '$http', function ($scope, $http) {
        $scope.includeArray = [];
        $scope.linkSelection = {
            type: null,
            value: null
        };


        var linkValue = null;

        $scope.link = {
            setValue: function(value) {
                linkValue = value;
                $scope.linkSelection.value = value;
            },
            getValue: function(){
                return linkValue;
            }
        };

        $scope.change = function(){
            if ($scope.linkType.length > 0) {
                $scope.includeArray = ['/link/' + $scope.linkType + '.html'];

                $scope.linkSelection.type = $scope.linkType;

                return;
            }

            $scope.includeArray = [];
        };

        $scope.ok = function () {
            if (linkValue == null || $scope.linkType.length == 0) {

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

                })
                .error(function (){
                });

        };

        $scope.cancel = function () {
        };
    }]);;angular.module('admin42')
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
;angular.module('admin42')
    .controller('WysiwygController', function ($scope, $attrs, $http) {
        if ($attrs.ngBaseUrl) {
            tinymce.baseURL = $attrs.ngBaseUrl;
        }

        $scope.tinymceOptionsFull = {
            trusted: true,
            format: 'raw',
            file_browser_callback: fileBrowser,
            plugins: 'paste autolink lists charmap table code link' +
                //'media link42 image42 ' +
            '',
            menubar: false,
            // == http://www.tinymce.com/wiki.php/Controls
            toolbar: 'undo redo paste | styleselect | bold italic | link unlink | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | removeformat | table code | ' +
                //"link42 image42 | " +
            '',
            skin: 'lightgray',
            theme: 'modern',
            elementpath: false,
            resize: true
        };

        // == http://www.tinymce.com/wiki.php/TinyMCE3x:How-to_implement_a_custom_file_browser
        // == http://michaelbudd.org/tutorials/view/28/building-custom-file-browser-for-tinymce-version-4
        function fileBrowser(field_name, url, type, win) {

            var fileBrowserUrl = $attrs.ngFileBrowser;
            var dialogTitles = angular.fromJson($attrs.ngFileBrowserTitles) || {};

            if (!fileBrowserUrl) {
                alert('File browser URL missing (ng-file-browser)');
                return;
            }

            fileBrowserUrl += '?type=' + type;

            // add currently selected file to query params
            var current = $('#' + field_name).val();
            if (current !== '') {
                fileBrowserUrl += '&current=' + encodeURIComponent(current);
            }

            var dialog = tinymce.activeEditor.windowManager.open({
                file: fileBrowserUrl,
                title: dialogTitles[type] || "File Browser",
                width: 800,
                height: 450,
                resizable: "yes",
                buttons: [
                    // possibility to make selection that has to be confirmed via done button instead of immediately selecting and closing dialog (pull from iframe dialog into inline dialog)
                    {
                        text: 'Done',
                        onclick: function (e) {
                            var frame = $(e.currentTarget).find("iframe").get(0);
                            var content = frame.contentDocument;
                            var selectedItem = $(content).find('input#linkSelection');
                            var linkData = selectedItem.val();
                            if (linkData.length == 0) {
                                top.tinymce.activeEditor.windowManager.close();
                            }

                            linkData = angular.fromJson(linkData);

                            if (angular.isUndefined(linkData.type)) {
                                top.tinymce.activeEditor.windowManager.close();
                            }

                            $http({
                                method: "POST",
                                url: $attrs.ngLinkSaveUrl,
                                data: linkData
                            })
                                .success(function (data){
                                    win.document.getElementById(field_name).value = data.url;
                                    top.tinymce.activeEditor.windowManager.close();
                                })
                                .error(function (){
                                    top.tinymce.activeEditor.windowManager.close();
                                });

                        }
                    },
                    {
                        text: 'Cancel',
                        onclick: function (e) {
                            top.tinymce.activeEditor.windowManager.close();
                        }
                    }
                ]
            }, {
                // adding params just in case
                field_name: field_name,
                type: type,
                win: win
            });
        }
    });
;angular.module('admin42')
    .service('MediaService', ['jsonCache', function(jsonCache) {
        this.getMediaUrl = function(directory, filename, mimeType, dimension) {
            if (angular.isUndefined(directory) || angular.isUndefined(filename)) {
                return "";
            }
            var mediaConfig = jsonCache.get("mediaConfig");

            directory = directory.replace("data/media", "");

            if (mimeType.substr(0, 6) != "image/" || dimension == null) {
                return mediaConfig.baseUrl + directory + filename;
            }

            if (angular.isUndefined(mediaConfig.dimensions[dimension])) {
                return mediaConfig.baseUrl + directory + filename;
            }

            var currentDimension = mediaConfig.dimensions[dimension];

            var extension = filename.split(".").pop();
            var oldFilename = filename;
            filename = filename.substr(0, filename.length - extension.length -1);

            filename = filename + "-" + ((currentDimension.width == "auto") ? "" : currentDimension.width) + "x" + ((currentDimension.height == "auto") ? "" : currentDimension.height) + "." + extension;

            return mediaConfig.baseUrl + directory + filename;
        }
    }]
);