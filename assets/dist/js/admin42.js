angular.module('admin42', [
    'ui.bootstrap',
    'ngAnimate',
    'ngSanitize',
    'ui.validate',
    'smart-table',
    'toaster',
    'ngStorage',
    'ui.select',
    'ui.tree'
]);

angular.module('admin42').config(['$httpProvider', '$compileProvider', function($httpProvider, $compileProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
    $compileProvider.debugInfoEnabled(false);
}]);
;
angular.module('admin42')
    .factory('$formService', ['$cacheFactory', 'jsonCache', function($cacheFactory, jsonCache) {
        var hash = {}
        function getFormFactory(formId) {
            var name = "formService" + formId;

            if (!hash.hasOwnProperty(name)) {
                hash[name] = $cacheFactory(name);
            }
            return hash[name];
        }

        return {
            get: function(formId, key) {
                return jsonCache.get(getFormFactory(formId).get(key));
            },
            put: function (formId, key, value) {
                return getFormFactory(formId).put(key, value);
            },
            remove: function(formId, key) {
                return getFormFactory(formId).remove(key);
            },
            removeAll: function(formId) {
                return getFormFactory(formId).removeAll();
            },
            destroy: function(formId) {
                return getFormFactory(formId).destroy();
            },
            info: function(formId) {
                return getFormFactory(formId).info();
            }
        }
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
    .directive('stReset', function () {
        return {
            restrict: 'EA',
            require: '^stTable',
            link: function (scope, element, attr, ctrl) {

                return element.bind('click', function() {
                    return scope.$apply(function() {
                        var tableState;
                        tableState = ctrl.tableState();
                        tableState.search.predicateObject = {};
                        tableState.pagination.start = 0;
                        return ctrl.pipe();
                    });
                });

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

                scope.btnClass = "btn-primary";
                if (!angular.isUndefined(attrs.btnClass)) {
                    scope.btnClass = attrs.btnClass;
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
    .directive('tinymce', ['$rootScope', '$compile', '$timeout', '$window', '$sce', 'jsonCache', function($rootScope, $compile, $timeout, $window, $sce, jsonCache) {
        return {
            require: ['ngModel'],
            priority: 599,
            link: function(scope, element, attrs, ctrls) {

                if (!$window.tinymce) {
                    return;
                }

                var ngModel = ctrls[0];
                var updateView = function(editor) {
                    var content = editor.getContent({format: options.format}).trim();
                    content = $sce.trustAsHtml(content);

                    ngModel.$setViewValue(content);
                    if (!$rootScope.$$phase) {
                        scope.$digest();
                    }
                };

                var expression = {};
                angular.extend(expression, scope.$eval(attrs.options));
                var options = {
                    setup: function(ed) {
                        ed.on('init', function() {
                            ngModel.$render();
                            ngModel.$setPristine();
                            ngModel.$setUntouched();
                        });

                        ed.on('ExecCommand change NodeChange ObjectResized', function() {
                            if (!options.debounce) {
                                ed.save();
                                updateView(ed);
                                return;
                            }
                            debouncedUpdate(ed);
                        });

                        ed.on('blur', function() {
                            element[0].blur();
                            ngModel.$setTouched();
                            if (!$rootScope.$$phase) {
                                scope.$digest();
                            }
                        });

                        ed.on('remove', function() {
                            //element.remove();
                        });

                        if (expression.setup) {
                            expression.setup(ed, {
                                updateView: updateView
                            });
                        }
                    },
                    format: 'html',
                    selector: '#' + attrs.id
                };
                angular.extend(options, expression);

                var debouncedUpdate = (function(debouncedUpdateDelay) {
                    var debouncedUpdateTimer;
                    return function(ed) {
                        $timeout.cancel(debouncedUpdateTimer);
                        debouncedUpdateTimer = $timeout(function() {
                            return (function(ed) {
                                if (ed.isDirty()) {
                                    ed.save();
                                    updateView(ed);
                                }
                            })(ed);
                        }, debouncedUpdateDelay);
                    };
                })(400);

                $timeout(function() {
                    if (options.baseURL){
                        tinymce.baseURL = options.baseURL;
                    }
                    tinymce.init(options);
                });

                ngModel.$formatters.unshift(function(modelValue) {
                    return modelValue ? $sce.trustAsHtml(modelValue) : '';
                });

                ngModel.$parsers.unshift(function(viewValue) {
                    return viewValue ? $sce.getTrustedHtml(viewValue) : '';
                });

                ngModel.$render = function() {
                    var tinyInstance = getInstance();

                    var viewValue = ngModel.$viewValue ? $sce.getTrustedHtml(ngModel.$viewValue) : '';

                    if (tinyInstance && tinyInstance.getDoc()) {
                        tinyInstance.setContent(viewValue);
                        tinyInstance.fire('change');
                    }
                };

                scope.$on('$tinyWysiwyg:disable', function() {
                    tinymce.EditorManager.execCommand('mceRemoveEditor', false, attrs.id);
                });

                scope.$on('$tinyWysiwyg:enable', function() {
                    console.log(attrs.id);
                    tinymce.EditorManager.execCommand('mceAddEditor', false, attrs.id);
                });

                function getInstance() {
                    return tinymce.get(attrs.id);
                }
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
    .directive('formCheckbox', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', '$formService', 'jsonCache', function($scope, $formService, jsonCache) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.model = ($scope.formData.value == $scope.formData.checkedValue);

                $scope.onChange = function () {
                    setValue();
                    $scope.formData.errors = [];
                };

                function setValue() {
                    $scope.formData.value = ($scope.model == true) ? $scope.formData.checkedValue : $scope.formData.uncheckedValue;
                }

                setValue();

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
;
angular.module('admin42')
    .directive('formCsrf', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

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
;
angular.module('admin42')
    .directive('formDate', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', '$filter', function($scope, jsonCache, $formService, $filter) {
                $scope.formData = jsonCache.get($scope.elementDataId);
                var date = $scope.formData.value;
                if (date.length > 0) {
                    $scope.value = moment(date).toDate();
                } else {
                    $scope.value = "";
                }
                $scope.popup = {
                    opened: false
                };

                $scope.open = function($event) {
                    $event.preventDefault();
                    $event.stopPropagation();
                    $scope.popup.opened = true;
                };

                $scope.preventEnter = function($event) {
                    if ($event.keyCode != 13) {
                        return;
                    }
                    $event.preventDefault();
                };

                $scope.empty = function() {
                    $scope.value = "";
                };

                $scope.$watch('value',function(newValue, oldValue) {
                    if(newValue != oldValue) {
                        if (newValue == "") {
                            $scope.formData.value = "";
                            return;
                        }
                        $scope.formData.value = $filter('date')(newValue, 'yyyy-MM-dd');
                    }
                },true);

                $scope.dateOptions = {
                    formatYear: 'yy',
                    startingDay: 1,
                    showWeeks: false
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
;
angular.module('admin42')
    .directive('formDatetime', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', 'appConfig', '$formService', function($scope, jsonCache, appConfig, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);
                var date = $scope.formData.value;
                if (date.length > 0) {
                    $scope.date = moment.tz(moment.utc(date), 'UTC').toDate();
                    $scope.time = $scope.date;
                } else {
                    $scope.date = null;
                    $scope.time = null;
                }
                $scope.popup = {
                    opened: false
                };

                $scope.open = function($event) {
                    $event.preventDefault();
                    $event.stopPropagation();
                    $scope.popup.opened = true;
                };

                $scope.dateOptions = {
                    formatYear: 'yy',
                    startingDay: 1,
                    showWeeks: false
                };

                $scope.preventEnter = function($event) {
                    if ($event.keyCode != 13) {
                        return;
                    }
                    $event.preventDefault();
                };

                $scope.empty = function() {
                    $scope.date = null;
                    $scope.time = null;
                };

                $scope.$watch('date',function(newValue, oldValue) {
                    if(newValue != oldValue) {
                        if(newValue != oldValue) {
                            $scope.formData.value = getValue(newValue, $scope.time);
                        }
                    }
                },true);

                $scope.$watch('time',function(newValue, oldValue) {
                    if(newValue != oldValue) {
                        $scope.formData.value = getValue($scope.date, newValue);
                    }
                },true);

                function getValue(date, time) {
                    if (date == null || time ==  null) {
                        return "";
                    }

                    date = moment.tz(moment.utc(date), appConfig.displayTimezone);
                    time = moment.tz(moment.utc(time), appConfig.displayTimezone);

                    var result = moment.tz(date.format("YYYY-MM-DD") + " " + time.format("HH:mm"), appConfig.displayTimezone);
                    return result.tz('UTC').format("YYYY-MM-DD HH:mm") + ":00";
                }

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
;
angular.module('admin42')
    .directive('formEmail', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.onChange = function () {
                    $scope.formData.errors = [];
                };

                $scope.empty = function() {
                    $scope.formData.value = "";
                    $scope.onChange();
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
;
angular.module('admin42')
    .directive('formFieldset', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$templateCache', '$formService', function($scope, jsonCache, $templateCache, $formService) {
                var elementData = jsonCache.get($scope.elementDataId);
                $scope.formData = elementData;
                $scope.formData.collapse = elementData.collapseAble;

                $scope.elements = [];

                angular.forEach(elementData.elements, function(element){
                    var id = elementData.id + "-" + $scope.elements.length;

                    var templateName = 'element/form/fieldset/' + id + '.html';

                    var elementOrFieldsetData = angular.copy(jsonCache.get(element.elementDataId));
                    var elementOrFieldsetDataKey = 'element/form/value/' + id + '.json';

                    elementOrFieldsetData.id = id;
                    elementOrFieldsetData.name = elementData.name + '[' + elementOrFieldsetData.options.originalName + ']';

                    jsonCache.put(elementOrFieldsetDataKey, elementOrFieldsetData);
                    $templateCache.put(
                        templateName,
                        '<' + element.directive + ' element-data-id="' + elementOrFieldsetDataKey +'" template="'+ elementOrFieldsetData.template +'"></' + element.directive + '>'
                    );

                    $scope.elements.push({
                        template: templateName,
                        elementDataId: elementOrFieldsetDataKey
                    });
                });

                if (angular.isDefined(elementData.options.formServiceHash)) {
                    $formService.put(
                        elementData.options.formServiceHash,
                        elementData.name,
                        $scope.elementDataId
                    );
                }
            }]
        }
    }]);
;
angular.module('admin42')
    .directive('formFile', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.onChange = function () {
                    $scope.formData.errors = [];
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
;
angular.module('admin42')
    .directive('formHidden', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

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
;
angular.module('admin42')
    .directive('formLink', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$uibModal', '$sce', '$formService', function($scope, jsonCache, $uibModal, $sce, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.getUrl = function() {
                    return $sce.trustAsResourceUrl($scope.formData.value.previewUrl);
                };

                $scope.empty = function() {
                    $scope.formData.value = {
                        linkId: null,
                        linkType: null,
                        linkValue: null,
                        linkDisplayName: null,
                        previewUrl: null
                    };
                    $scope.formData.errors = [];
                };

                if (angular.isDefined($scope.formData.options.formServiceHash)) {
                    $formService.put(
                        $scope.formData.options.formServiceHash,
                        $scope.formData.name,
                        $scope.elementDataId
                    );
                }

                $scope.selectLink = function() {
                    var modalInstance = $uibModal.open({
                        animation: true,
                        templateUrl: 'element/form/link-modal.html',
                        controller: ['$scope', '$uibModalInstance', 'formData', '$http', function ($scope, $uibModalInstance, formData, $http) {
                            $scope.linkData = formData.value;
                            $scope.availableLinkTypes = formData.availableLinkTypes;
                            $scope.includeArray = [];

                            $scope.link = {
                                setValue: function(value) {
                                    $scope.linkData.linkValue = value;
                                },
                                getValue: function(){
                                    return $scope.linkData.linkValue;
                                }
                            };

                            $scope.change = function(){
                                if ($scope.linkData.linkType.length > 0) {
                                    $scope.includeArray = ['link/' + $scope.linkData.linkType + '.html'];

                                    return;
                                }

                                $scope.includeArray = [];
                            };

                            if ($scope.linkData.linkType !== null) {
                                $scope.change();
                            }

                            $scope.ok = function () {
                                if ($scope.linkData.linkValue == null || $scope.linkData.linkType.length == 0) {
                                    $uibModalInstance.close({
                                        linkId: null,
                                        linkDisplayName: null,
                                        linkValue: null,
                                        linkType: null,
                                        previewUrl: null,
                                    });

                                    return;
                                }

                                $http({
                                    method: "POST",
                                    url: formData.saveUrl,
                                    data: {
                                        type: $scope.linkData.linkType,
                                        value: $scope.linkData.linkValue,
                                        id: $scope.linkData.linkId
                                    }
                                })
                                    .success(function (data){
                                        $uibModalInstance.close({
                                            linkId: data.linkId,
                                            linkDisplayName: data.linkDisplayName,
                                            linkValue: $scope.linkData.linkValue,
                                            previewUrl: data.url,
                                            linkType: $scope.linkData.linkType
                                        });
                                    })
                                    .error(function (){
                                        $uibModalInstance.dismiss('cancel');
                                    });

                            };

                            $scope.cancel = function (){
                                $uibModalInstance.dismiss('cancel');
                            };
                        }],
                        size: 'lg',
                        resolve: {
                            formData: function() {
                                return $scope.formData;
                            }
                        }
                    });

                    modalInstance.result.then(function(data) {
                        $scope.formData.value.linkId = data.linkId;
                        $scope.formData.value.linkDisplayName = data.linkDisplayName;
                        $scope.formData.value.linkValue = data.linkValue;
                        $scope.formData.value.linkType = data.linkType;
                        $scope.formData.value.previewUrl = data.previewUrl;
                        $scope.formData.errors = [];
                    }, function () {

                    });
                };
            }]
        }
    }]);
;
angular.module('admin42')
    .directive('formMoney', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@'
            },
            controller: ['$scope', 'jsonCache', '$formService', 'appConfig', function($scope, jsonCache, $formService, appConfig) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.onChange = function () {
                    $scope.formData.errors = [];
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
    }]);;
angular.module('admin42')
    .directive('formMulticheckbox', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);
                var initialValues = $scope.formData.value;
                $scope.values = [];
                $scope.options = $scope.formData.valueOptions;
                $scope.checkboxModel = {};
                
                angular.forEach($scope.options, function(option){
                    $scope.checkboxModel[option.id] = (initialValues.indexOf(option.id) != -1);
                });

                var initial = true;
                $scope.select = function(){
                    var values = [];
                    angular.forEach($scope.checkboxModel, function(value, index){
                        if (value === false) {
                            return;
                        }
                        values.push(index)
                    });
                    $scope.values = values;

                    if (initial === false) {
                        $scope.formData.errors = [];
                    }
                    initial = false;
                };

                $scope.select();

                $scope.empty = function() {
                    angular.forEach($scope.checkboxModel, function(value, index){
                        $scope.checkboxModel[index] = false;
                    });
                    $scope.select();
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
;
angular.module('admin42')
    .directive('formPassword', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.showPassword = false;

                $scope.empty = function() {
                    $scope.formData.value = "";
                    $scope.onChange();
                };

                $scope.preventEnter = function($event) {
                    if ($event.keyCode != 13) {
                        return;
                    }
                    $event.preventDefault();
                };

                $scope.togglePassword = function () {
                    $scope.showPassword = !$scope.showPassword;
                };
                
                $scope.getInputType = function () {
                    return ($scope.showPassword) ? 'text' : 'password';
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
;
angular.module('admin42')
    .directive('formRadio', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);
                $scope.options = $scope.formData.valueOptions;

                $scope.radioModel = $scope.formData.value;

                $scope.select = function(radioModel) {
                    $scope.formData.value = radioModel;
                    $scope.formData.errors = [];
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
;
angular.module('admin42')
    .directive('formSelect', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);
                $scope.formData.valueOption = {};

                $scope.searchEnabled = $scope.formData.valueOptions.length > 5;

                if (angular.isDefined($scope.formData.options.formServiceHash)) {
                    $formService.put(
                        $scope.formData.options.formServiceHash,
                        $scope.formData.name,
                        $scope.elementDataId
                    );
                }

                $scope.notEmpty = function() {
                    return angular.isDefined($scope.formData.value) && $scope.formData.value !== null &&
                                (angular.isNumber($scope.formData.value) || (angular.isString($scope.formData.value) && $scope.formData.value.length > 0));
                };

                $scope.select = function($item, $model){
                    $scope.formData.errors = [];
                    $scope.formData.value = $model.id;
                };

                $scope.empty = function() {
                    $scope.formData.value = $scope.formData.emptyValue;
                    $scope.formData.valueOption.selected = null;

                    $scope.formData.errors = [];
                };

                function setValue() {
                    if ($scope.notEmpty()) {
                        angular.forEach($scope.formData.valueOptions, function(option) {
                            if (option.id == $scope.formData.value) {
                                $scope.formData.valueOption.selected = option;
                            }
                        });
                    }
                }

                if (!$scope.notEmpty() && $scope.formData.emptyValue !== null) {
                    $scope.formData.value = $scope.formData.emptyValue;
                }
                setValue();
            }]
        }
    }]);
;
angular.module('admin42')
    .directive('formStack', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$templateCache', '$formService', function($scope, jsonCache, $templateCache, $formService) {
                var elementData = jsonCache.get($scope.elementDataId);
                $scope.label = elementData.label;

                $scope.protoTypes = elementData.protoTypes;
                $scope.data = {
                    selectedProtoType: $scope.protoTypes[0]
                };

                $scope.treeOptions = {};
                $scope.sortingMode = false;
                $scope.elements = [];

                angular.forEach(elementData.elements, function(element){
                    var id = elementData.id + "-" + $scope.elements.length;

                    var templateName = 'element/form/stack/' + id + '.html';

                    var elementOrFieldsetData = angular.copy(jsonCache.get(element.elementDataId));
                    var elementOrFieldsetDataKey = 'element/form/value/' + id + '.json';

                    elementOrFieldsetData.id = id;
                    elementOrFieldsetData.name = elementData.name + '[' + elementOrFieldsetData.options.originalName + ']';

                    jsonCache.put(elementOrFieldsetDataKey, elementOrFieldsetData);
                    $templateCache.put(
                        templateName,
                        '<' + element.directive + ' element-data-id="' + elementOrFieldsetDataKey +'" template="'+ elementOrFieldsetData.template +'"></' + element.directive + '>'
                    );

                    $scope.elements.push({
                        formName: elementOrFieldsetData.name,
                        type: elementOrFieldsetData.options.stackType,
                        name: elementOrFieldsetData.options.fieldsetName,
                        nameEditing: false,
                        template: templateName,
                        elementDataId: elementOrFieldsetDataKey,
                        label: elementOrFieldsetData.label,
                        deleted: elementOrFieldsetData.options.fieldsetDeleted,
                        collapsed: $scope.sortingMode,
                        collapsedState: false,
                        nodes: []
                    });
                });

                $scope.collapse = function() {
                    angular.forEach($scope.elements, function(element){
                        if (element.collapsed === true) {
                            return;
                        }
                        element.collapsedState = element.collapsed;
                        element.collapsed = true;
                    });
                }

                $scope.expand = function() {
                    angular.forEach($scope.elements, function(element){
                        element.collapsed = element.collapsedState;
                    });
                }

                $scope.preventEnter = function(element, $event) {
                    if ($event.keyCode != 13) {
                        return;
                    }
                    element.nameEditing = false;
                    $event.preventDefault();
                }

                $scope.startSortingMode = function() {
                    if ($scope.sortingMode === false) {
                        $scope.$broadcast('$dynamic:sort-start');

                        $scope.sortingMode = true;
                        $scope.collapse();

                        return;
                    }

                    $scope.expand();
                    $scope.$broadcast('$dynamic:sort-stop');
                    $scope.sortingMode = false;
                };
                
                $scope.addTemplate = function () {
                    var id = elementData.id + "-" + $scope.elements.length;
                    var element = $scope.data.selectedProtoType;

                    var templateName = 'element/form/stack/' + id + '.html';

                    var elementOrFieldsetData = angular.copy(jsonCache.get(element.elementData));
                    var elementOrFieldsetDataKey = 'element/form/value/' + id + '.json';

                    elementOrFieldsetData.id = id;
                    elementOrFieldsetData.options.originalName = id;
                    elementOrFieldsetData.name = elementData.name + '[' + elementOrFieldsetData['options']['originalName'] + ']';

                    jsonCache.put(elementOrFieldsetDataKey, elementOrFieldsetData);
                    $templateCache.put(
                        templateName,
                        '<' + element.directive + ' element-data-id="' + elementOrFieldsetDataKey +'" template="'+ elementOrFieldsetData.template +'"></' + element.directive + '>'
                    );

                    $scope.elements.push({
                        formName: elementOrFieldsetData.name,
                        type: elementOrFieldsetData.options.stackType,
                        name: "",
                        nameEditing: false,
                        template: templateName,
                        elementDataId: elementOrFieldsetDataKey,
                        label: elementOrFieldsetData.label,
                        deleted: false,
                        collapsed: $scope.sortingMode,
                        collapsedState: false,
                        nodes: []
                    });
                };

                if (angular.isDefined(elementData.options.formServiceHash)) {
                    $formService.put(
                        elementData.options.formServiceHash,
                        elementData.name,
                        $scope.elementDataId
                    );
                }
            }]
        }
    }]);
;
angular.module('admin42')
    .directive('formSwitcher', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.model = ($scope.formData.value == $scope.formData.checkedValue);

                function setValue() {
                    $scope.formData.value = ($scope.model == true) ? $scope.formData.checkedValue : $scope.formData.uncheckedValue;
                }
                setValue();

                $scope.onChange = function () {
                    setValue();
                    $scope.formData.errors = [];
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
;
angular.module('admin42')
    .directive('formText', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@'
            },
            controller: ['$scope', '$rootScope', 'jsonCache', '$formService', function($scope, $rootScope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.onChange = function() {
                    $rootScope.$broadcast('formElementChange', $scope.formData.name);
                    $scope.formData.errors = [];
                };

                $scope.onBlur = function() {
                    $rootScope.$broadcast('formElementBlur', $scope.formData.name);
                };

                $scope.empty = function() {
                    $scope.formData.value = "";
                    $scope.onChange();
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
;
angular.module('admin42')
    .directive('formTextarea', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$formService', function($scope, jsonCache, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.onChange = function () {
                    $scope.formData.errors = [];
                };

                $scope.empty = function() {
                    $scope.formData.value = "";
                    $scope.onChange();
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
;
angular.module('admin42')
    .directive('formWysiwyg', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', '$formService', 'jsonCache', function($scope, $formService, jsonCache) {
                $scope.formData = jsonCache.get($scope.elementDataId);

                $scope.$on('$dynamic:sort-start', function() {
                    $scope.$broadcast('$tinyWysiwyg:disable');
                });

                $scope.$on('$dynamic:sort-stop', function() {
                    $scope.$broadcast('$tinyWysiwyg:enable');
                });

                $scope.empty = function() {
                    $scope.formData.value = "";
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
;
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
                }

                $scope.videoUrl = function() {
                    return $sce.trustAsResourceUrl("https://www.youtube.com/embed/" + $scope.formData.value);
                }

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

            dateTime.defineLocale(appConfig.locale);
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
    .controller('LinkController', ['$scope', '$http', 'jsonCache', function ($scope, $http, jsonCache) {
        $scope.availableLinkTypes = jsonCache.get('link/availableAdapters.json');
        $scope.linkData = jsonCache.get('link/linkData.json');
        $scope.includeArray = [];

        $scope.change = function(){
            if ($scope.linkData.linkType.length > 0) {
                $scope.includeArray = ['link/' + $scope.linkData.linkType + '.html'];

                return;
            }

            $scope.includeArray = [];
        };

        if ($scope.linkData.linkType !== null) {
            $scope.change();
        }

        $scope.link = {
            setValue: function(value) {
                $scope.linkData.linkValue = value;
            },
            getValue: function(){
                return $scope.linkData.linkValue;
            }
        };
    }]);
;
angular.module('admin42').controller('ModalController', ['$scope', '$uibModalInstance', function ($scope, $uibModalInstance) {
    $scope.ok = function () {
        $uibModalInstance.close();
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
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
