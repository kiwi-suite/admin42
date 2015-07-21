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
    });