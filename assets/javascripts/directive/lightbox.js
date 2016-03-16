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
