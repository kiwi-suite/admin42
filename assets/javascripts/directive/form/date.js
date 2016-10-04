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
