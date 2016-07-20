angular.module('admin42', [
    'ui.bootstrap',
    'ui.bootstrap.datetimepicker',
    'ngAnimate',
    'ngSanitize',
    'ui.validate',
    'ui.indeterminate',
    'ui.mask',
    'ui.event',
    'ui.scroll',
    'ui.scrollpoint',
    'smart-table',
    'toaster',
    'ngStorage',
    'ui.sortable',
    'ui.select'
]);

angular.module('admin42').config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}]);
;
angular.module('admin42')
    .directive('delete', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/delete.html',
            scope: {
                callback: "="
            },
            controller: ['$scope', '$attrs', '$uibModal', '$window', '$parse', function($scope, $attrs, $uibModal, $window, $parse) {
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

                $scope.icon = "fa fa-trash-o";
                if (!angular.isUndefined($attrs.icon)) {
                    $scope.icon = $attrs.icon;
                }

                $scope.deleteLoading = false;

                $scope.delete = function() {
                    $scope.deleteLoading = true;
                    var modalInstance = $uibModal.open({
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
                            },
                            requestIcon: function(){
                                return $scope.icon;
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

                        $scope.deleteLoading = false;
                    }, function () {
                        $scope.deleteLoading = false;
                    });
                }
            }]
        };
    }]);

angular.module('admin42')
    .controller('DeleteModalController', [
            '$scope',
            '$uibModalInstance',
            '$http',
            'requestUrl',
            'requestParams',
            'requestTitle',
            'requestContent',
            'requestMethod',
            'requestIcon',
            function ($scope, $uibModalInstance, $http, requestUrl, requestParams, requestTitle, requestContent, requestMethod, requestIcon) {
                $scope.title = requestTitle;
                $scope.content = requestContent;
                $scope.icon = requestIcon;
                $scope.deleteLoading = false;

                $scope.ok = function () {
                    $scope.deleteLoading = true;
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
                            $uibModalInstance.close(data);
                        })
                        .error(function (){
                            $uibModalInstance.dismiss('cancel');
                        });
                };

                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
    }]);
;
angular.module('admin42')
    .directive('adminDynamicForm', function() {
        return {
            restrict: 'A',
            scope: {
                templateName: '@',
                adminDynamicFormElements: '@',
                adminDynamicPrototypes: '@',
                parentHashes: '='
            },
            templateUrl: function(elem,attrs){
                return attrs.baseForm;
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.data = {};
                $scope.elements = angular.copy(jsonCache.get($scope.adminDynamicFormElements));
                $scope.prototypes = jsonCache.get($scope.adminDynamicPrototypes);

                $scope.data.selectedPrototype = $scope.prototypes[0];

                $scope.makeid = function(){
                    var text = "";
                    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                    for( var i=0; i<8; i++ ){
                        text += possible.charAt(Math.floor(Math.random() * possible.length));
                    }
                    return "ph" + text;
                };

                $scope.addTemplate = function() {
                    var element = angular.copy($scope.data.selectedPrototype);
                    element.hash = $scope.makeid();
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
    });
;
angular.module('admin42').directive('dynamicModel', ['$compile', function ($compile) {
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
}]);;
angular.module('admin42')
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
;
angular.module('admin42')
    .directive('lightbox', [function() {
        return {
            restrict: 'A',
            link: function(scope, elem, attrs) {
                $(elem).magnificPopup({
                    type: 'image',
                    closeOnContentClick: true,
                    mainClass: 'mfp-img-mobile',
                    image: {
                        verticalFit: true
                    }

                });
            }
        };
    }]);
;
angular.module('admin42')
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
;
angular.module('admin42')
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
;
angular.module('admin42')
    .directive('stPersist', function () {
        return {
            require: '^stTable',
            link: function (scope, element, attr, ctrl) {
                var nameSpace = attr.stPersist;

                var forceReload = (angular.isDefined(attr.stPersistForceReload));

                //save the table state every time it changes
                scope.$watch(function () {
                    return ctrl.tableState();
                }, function (newValue, oldValue) {
                    if (newValue !== oldValue) {
                        localStorage.setItem(nameSpace, JSON.stringify(newValue));
                    }
                }, true);

                //fetch the table state when the directive is loaded
                if (!forceReload && localStorage.getItem(nameSpace)) {
                    var savedState = JSON.parse(localStorage.getItem(nameSpace));
                    var tableState = ctrl.tableState();

                    angular.extend(tableState, savedState);
                    ctrl.pipe();

                }

            }
        };
    });
;
angular.module('smart-table')
    .directive('stSearch42', ['stConfig', '$timeout','$parse', function (stConfig, $timeout, $parse) {
        return {
            require: '^stTable',
            link: function (scope, element, attr, ctrl) {
                var tableCtrl = ctrl;
                var promise = null;
                var throttle = attr.stDelay || stConfig.search.delay;
                var event = attr.stInputEvent || stConfig.search.inputEvent;

                attr.$observe('stSearch42', function (newValue, oldValue) {
                    var input = element[0].value;
                    if (newValue !== oldValue && input) {
                        //ctrl.tableState().search = {};
                        tableCtrl.search(input, newValue);
                    }
                });

                //table state -> view
                scope.$watch(function () {
                    return ctrl.tableState().search;
                }, function (newValue, oldValue) {
                    var predicateExpression = attr.stSearch42 || '$';
                    if (newValue.predicateObject && $parse(predicateExpression)(newValue.predicateObject) !== element[0].value) {
                        element[0].value = $parse(predicateExpression)(newValue.predicateObject) || '';
                    }
                }, true);

                // view -> table state
                element.bind(event, function (evt) {
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
;
angular.module('admin42')
    .directive('submit', [function() {
        return {
            restrict: 'E',
            replace: true,
            scope: {},
            templateUrl: 'element/submit.html',
            link: function(scope, elem, attrs, ctrl) {
                scope.icon = "fa fa-save";
                if (!angular.isUndefined(attrs.icon)) {
                    scope.icon = attrs.icon;
                }

                scope.submitText = "Save";
                if (!angular.isUndefined(attrs.submitText)) {
                    scope.submitText = attrs.submitText;
                }

                scope.submitLoading = false;

                elem.bind('click', function(event) {
                    if (scope.submitLoading == true) {
                        event.preventDefault();

                        return;
                    }
                    scope.$apply(
                        function(){
                            scope.submitLoading = true;
                        }
                    );
                });
            }
        };
    }]);
;
angular.module('admin42')
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
  }]);;
angular.module('admin42')
    .directive('uibTabsetPersist', function () {
        return {
            require: '^uibTabset',
            link: function (scope, element, attr, ctrl) {
                var nameSpace = 'uibTabsetPersist';
                var id = attr.uibTabsetPersist;

                var currentItem = JSON.parse(localStorage.getItem(nameSpace));

                if (currentItem && currentItem.id == id) {
                    //ctrl.select(ctrl.tabs[currentItem.index])
                }

                ctrl.select = function (selectedTab) {
                    console.log(ctrl);
                    /*ctrl.tabs.forEach(function (tab, index) {
                        tab.active = false;
                        if (tab == selectedTab) {
                            tab.active = true;
                            localStorage.setItem(nameSpace, JSON.stringify({id: id, index: index}));
                        }
                    });*/
                };
            }
        };
    });
;
angular.module('admin42')
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
;
angular.module('admin42')
    .directive('youtube', [function () {
        return {
            restrict: 'E',
            scope: true,
            transclude: false,
            controller: ['$scope', '$sce', '$attrs',  function($scope, $sce, $attrs) {
                $scope.youtubeCode = $attrs.youtubeCode;
                if ($scope.youtubeCode.length > 0) {
                    $scope.youtubeLink = "https://www.youtube.com/watch?v=" + $scope.youtubeCode;
                }
                $scope.changeValue = function() {
                    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                    var match = $scope.youtubeLink.match(regExp);
                    if (match && match[2].length == 11) {
                        $scope.youtubeCode = match[2];
                        $scope.youtubeLink = "https://www.youtube.com/watch?v=" + $scope.youtubeCode;
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
;
angular.module('admin42')
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
    });;
angular.module('admin42')
    .filter('datetime', function(appConfig) {
        return function(input, emptyValue, format, timezone) {
            if (angular.isUndefined(emptyValue)) {
                emptyValue = "-";
            }
            if (angular.isUndefined(format)) {
                format = appConfig.defaultDateTimeFormat;
            }
            if (angular.isUndefined(timezone)) {
                timezone = appConfig.displayTimezone;
            }

            var dateTime;
            if (angular.isObject(input) && Object.prototype.toString.call(input) == '[object Date]') {
                dateTime = moment.tz(moment.utc(input), input.timezone);
                dateTime = moment.tz(dateTime, timezone);
            } else if (angular.isObject(input)) {
                if (angular.isUndefined(input.date)) {
                    return emptyValue;
                }
                dateTime = moment.tz(moment.utc(input.date), input.timezone);
                dateTime = moment.tz(dateTime, timezone);
            } else if (angular.isString(input)) {
                if (input.length == 0) {
                    return emptyValue;
                }
                dateTime = moment.tz(moment.utc(input), timezone);
            } else {
                return emptyValue;
            }

            dateTime.locale(appConfig.locale);
            return dateTime.format(format);
        };
    });
;
angular.module('admin42').controller('AppController',['$scope', 'toaster', '$timeout', '$localStorage', function($scope, toaster, $timeout, $localStorage){
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
;
angular.module('admin42').controller('DataGridController',['$scope', '$http', '$attrs', '$sessionStorage', '$templateCache', function($scope, $http, $attrs, $sessionStorage, $templateCache){
    $templateCache.put('template/smart-table/pagination.html',
        '<nav ng-if="numPages && pages.length >= 2"><ul class="pagination">' +
        '<li ng-if="currentPage > 1"><a ng-click="selectPage(1)"><i class="fa fa-angle-double-left"></i></a></li>' +
        '<li ng-if="currentPage > 1"><a ng-click="selectPage(currentPage - 1)"><i class="fa fa-angle-left"></i></a></li>' +
        '<li ng-repeat="page in pages" ng-class="{active: page==currentPage}"><a ng-click="selectPage(page)">{{page}}</a></li>' +
        '<li ng-if="currentPage < numPages"><a ng-click="selectPage(currentPage + 1)"><i class="fa fa-angle-right"></i></a></li>' +
        '<li ng-if="currentPage < numPages"><a ng-click="selectPage(numPages)"><i class="fa fa-angle-double-right"></i></a></li>' +
        '</ul></nav>');

    var url = $attrs.url;
    var isInitialCall = true;
    var persistNamespace = null;

    if (angular.isDefined($attrs.persist) && $attrs.persist.length > 0) {
        persistNamespace = $attrs.persist;
    }

    $scope.collection = [];
    $scope.isLoading = true;
    $scope.displayedPages = 1;

    $scope.callServer = function (tableState) {
        $scope.collection = [];
        $scope.isLoading = true;

        if (isInitialCall === true && persistNamespace !== null) {
            if (angular.isDefined($sessionStorage.smartTable) && angular.isDefined($sessionStorage.smartTable[persistNamespace])) {
                angular.extend(tableState, angular.fromJson($sessionStorage.smartTable[persistNamespace]));
            }
        } else if (persistNamespace !== null) {
            if (angular.isUndefined($sessionStorage.smartTable)) {
                $sessionStorage.smartTable = {};
            }
            $sessionStorage.smartTable[persistNamespace] = angular.toJson(tableState);
        }

        isInitialCall = false;
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
;
angular.module('admin42')
    .controller('AdminDatepickerController',['$scope', '$attrs', function($scope, $attrs){
        $scope.opened = false;

        $scope.open = function($event) {
            $event.preventDefault();
            $event.stopPropagation();

            $scope.opened = true;
        };

        if ($attrs.value.length > 0) {
            eval("$scope." + $attrs.modelName + "=moment($attrs.value).toDate()");
        }

        /*if ($attrs.value.length > 0) {
            var segs = $attrs.modelName.split('.');
            var data = $scope;
            while (segs.length > 0) {
                var pathStep = segs.shift();
                if (typeof data[pathStep] === 'undefined') {
                    data[pathStep] = segs.length === 0 ? moment($attrs.value).toDate() : {};
                    console.log(data[pathStep]);
                }
                data = data[pathStep];
            }
        }*/


        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1,
            enableDate: true,
            enableTime: true,
            class: 'datepicker',
            showWeeks: false,
            timeText: 'Time'
        };
}]);
;
angular.module('admin42')
    .controller('LinkController',['$scope', '$attrs', '$uibModal', 'jsonCache', function($scope, $attrs, $uibModal, jsonCache){
        $scope.linkId = jsonCache.get($attrs.jsonDataId)['linkId'];
        $scope.linkType = jsonCache.get($attrs.jsonDataId)['linkType'];
        $scope.linkValue = jsonCache.get($attrs.jsonDataId)['linkValue'];
        $scope.linkDisplayName = jsonCache.get($attrs.jsonDataId)['linkDisplayName'];

        $scope.selectLink = function() {
            var modalInstance = $uibModal.open({
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
    .controller('LinkModalSelectorController', ['$scope', '$uibModalInstance', '$http', 'linkSaveUrl', 'linkType', 'linkValue', 'linkId', function ($scope, $uibModalInstance, $http, linkSaveUrl, linkType, linkValue, linkId) {
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
                $uibModalInstance.close({
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
                    $uibModalInstance.close({
                        linkId: data.linkId,
                        linkDisplayName: data.linkDisplayName,
                        linkValue: linkValue,
                        linkType: $scope.linkType
                    });
                })
                .error(function (){
                    $uibModalInstance.dismiss('cancel');
                });

        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
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
;
angular.module('admin42')
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
    }]);;
angular.module('admin42').controller('ModalController', ['$scope', '$uibModalInstance', function ($scope, $uibModalInstance) {
    $scope.ok = function () {
        $uibModalInstance.close();
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
}]);
;
angular.module('admin42').controller('NotificationController', ['$scope', '$interval', '$http', '$attrs', function($scope, $interval, $http, $attrs){
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
;
angular.module('admin42')
    .controller('SelectController', ['$scope', '$attrs', 'jsonCache', function($scope, $attrs, jsonCache){
        $scope.options = jsonCache.get($attrs.jsonDataId);
        $scope.option = {};
        
        $scope.select = function(id){
            angular.forEach($scope.options, function(option){
                if (option.id == id) {
                    $scope.option.selected = option;
                }
            });
        };
        
        if ($attrs.initValue && $attrs.initValue.length > 0) {
            $scope.select($attrs.initValue);
        }
    }]);
;
angular.module('admin42')
    .controller('TagsElementController', ['$scope', '$attrs', '$http', '$q', 'jsonCache', function ($scope, $attrs, $http, $q, jsonCache) {
        var url = $attrs.url;

        var canceler = null;

        $scope.tags = {
            tags: [],
            //selectedTags: [{"id": 1, "tag": "foobar"},{"id": 2, "tag": "test"}]
            selectedTags: jsonCache.get($attrs.jsonDataId),
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

        $scope.updateHiddenField();

        $scope.refreshTags = function(tag) {

            $scope.tags.tags = [];

            var params = {tag: tag};

            if (tag.length > 0) {

                if (canceler != null) {
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
;
angular.module('admin42')
    .controller('WysiwygController', function ($scope, $attrs, $http, jsonCache) {
        if ($attrs.ngBaseUrl) {
            tinymce.baseURL = $attrs.ngBaseUrl;
        }

        $scope.tinymceOptionsFull = jsonCache.get($attrs.ngJsonDataId);
        $scope.tinymceOptionsFull.file_browser_callback = fileBrowser;

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
