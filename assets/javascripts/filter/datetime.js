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
