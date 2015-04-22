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
                timezone = appConfig.timezone;
            }
            console.log(format);
            var dateTime;
            if (angular.isObject(input)) {
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
