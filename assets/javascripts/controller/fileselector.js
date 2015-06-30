angular.module('admin42')
    .controller('FileSelectorController', ['$scope', '$attrs', function ($scope, $attrs) {

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
    }]);
