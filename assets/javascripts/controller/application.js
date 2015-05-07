angular.module('admin42').controller('AppController',['$scope', 'toaster', '$timeout', '$localStorage', function($scope, toaster, $timeout, $localStorage){
    $scope.app = {
        $storage: $localStorage.$default({
            asideFolded: false
        })
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
