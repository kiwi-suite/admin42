angular.module('admin42')
    .directive('formDate', [function() {
        return {
            restrict: 'E',
            templateUrl: 'element/form/date.html',
            scope: {
                jsonCacheId: '@jsonCacheId'
            },
            controller: ['$scope', 'jsonCache', function($scope, jsonCache) {
                $scope.formData = jsonCache.get($scope.jsonCacheId);
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

                $scope.dateOptions = {
                    formatYear: 'yy',
                    startingDay: 1,
                    showWeeks: false
                };
            }]
        }
    }]);
