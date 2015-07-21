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
