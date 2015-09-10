angular.module('admin42').controller('DataGridController',['$scope', '$http', '$attrs', '$sessionStorage', '$templateCache', function($scope, $http, $attrs, $sessionStorage, $templateCache){
    $templateCache.put('template/smart-table/pagination.html',
        '<nav ng-if="numPages && pages.length >= 2"><ul class="pagination">' +
        '<li ng-if="currentPage > 1"><a ng-click="selectPage(1)"><i class="fa fa-angle-double-left"></i></a></li>' +
        '<li ng-if="currentPage > 1"><a ng-click="selectPage(currentPage - 1)"><i class="fa fa-angle-left"></i></a></li>' +
        '<li ng-repeat="page in pages" ng-class="{active: page==currentPage}"><a ng-click="selectPage(page)">{{page}}</a></li>' +
        '<li ng-if="currentPage < numPages"><a ng-click="selectPage(currentPage + 1)"><i class="fa fa-angle-right"></i></a></li>' +
        '<li ng-if="currentPage < numPages"><a ng-click="selectPage(numPages)"><i class="fa fa-angle-double-right"></i></a></li>' +
        '</ul></nav>');

    var url = $attrs.url;
    var isInitialCall = true;
    var persistNamespace = null;

    if (angular.isDefined($attrs.persist) && $attrs.persist.length > 0) {
        persistNamespace = $attrs.persist;
    }

    $scope.collection = [];
    $scope.isLoading = true;
    $scope.displayedPages = 1;

    $scope.callServer = function (tableState) {
        $scope.collection = [];
        $scope.isLoading = true;

        if (isInitialCall === true && persistNamespace !== null) {
            if (angular.isDefined($sessionStorage.smartTable) && angular.isDefined($sessionStorage.smartTable[persistNamespace])) {
                angular.extend(tableState, angular.fromJson($sessionStorage.smartTable[persistNamespace]));
            }
        } else if (persistNamespace !== null) {
            if (angular.isUndefined($sessionStorage.smartTable)) {
                $sessionStorage.smartTable = {};
            }
            $sessionStorage.smartTable[persistNamespace] = angular.toJson(tableState);
        }

        isInitialCall = false;
        $http.post(url, tableState).
            success(function(data, status, headers, config) {
                $scope.isLoading = false;

                $scope.collection = data.data;

                $scope.displayedPages = data.meta.displayedPages;
                tableState.pagination.numberOfPages = data.meta.displayedPages;
            }).
            error(function(data, status, headers, config) {
            });
    };
}]);
