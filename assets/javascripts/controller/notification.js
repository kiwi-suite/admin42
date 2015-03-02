angular.module('admin42').controller('NotificationController', ['$scope', '$interval', '$http', '$attrs', function($scope, $interval, $http, $attrs){
        var notificationUrl = $attrs.notificationUrl;
        var updateNotifications = function() {
            $http.get(notificationUrl).
                success(function(data) {
                    $scope.notifications = data;
                }).
                error(function() {
                    $scope.notifications = [];
                });
        };

        $scope.notifications = [];
        $interval(updateNotifications, 30000);
        updateNotifications();

        $scope.clearNotifications = function(clearUrl) {
            $http.post(clearUrl).
                success(function() {
                    $scope.notifications = [];
                }).
                error(function() {});
        };
    }]);
