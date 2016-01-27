angular.module('admin42')
    .controller('AdminDatepickerController',['$scope', '$attrs', function($scope, $attrs){
        $scope.opened = false;

        $scope.open = function($event) {
            $event.preventDefault();
            $event.stopPropagation();

            $scope.opened = true;
        };

        if ($attrs.value.length > 0) {
            eval("$scope." + $attrs.modelName + "=moment($attrs.value).toDate()");
        }

        /*if ($attrs.value.length > 0) {
            var segs = $attrs.modelName.split('.');
            var data = $scope;
            while (segs.length > 0) {
                var pathStep = segs.shift();
                if (typeof data[pathStep] === 'undefined') {
                    data[pathStep] = segs.length === 0 ? moment($attrs.value).toDate() : {};
                    console.log(data[pathStep]);
                }
                data = data[pathStep];
            }
        }*/


        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1,
            enableDate: true,
            enableTime: true,
            class: 'datepicker',
            showWeeks: false,
            timeText: 'Time'
        };
}]);
