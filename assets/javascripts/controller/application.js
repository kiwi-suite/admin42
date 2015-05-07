angular.module('admin42').controller('AppController',['$scope', 'toaster', '$timeout', function($scope, toaster, $timeout){
    $scope.app = {
        settings: {
            asideFolded: false
        }
    };

    $timeout(function(){
        angular.forEach(FLASH_MESSAGE, function(messages, namespace){
            if (messages.length == 0) {
                return;
            }

            angular.forEach(messages, function(message){
                toaster.pop(namespace, message.title, message.text);
            });
        });
    }, 1000);

}]);
