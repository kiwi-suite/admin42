angular.module('admin42')
    .directive('formDatetime', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/datetime.html',
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

                $scope.getValue = function(date, time) {
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
