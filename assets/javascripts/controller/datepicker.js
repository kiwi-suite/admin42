angular.module('admin42')
    .controller('AdminDatepickerController',['$scope', '$attrs', function($scope, $attrs){
        $scope.opened = false;

        $scope.open = function($event) {
            $event.preventDefault();
            $event.stopPropagation();

            $scope.opened = true;
        };

        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1,
            enableDate: true,
            enableTime: true,
            class: 'datepicker',
            showWeeks: false,
            timeText: 'Time',
            startingDay: 1
        };

        console.log($scope);
}]);
