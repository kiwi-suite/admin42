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
