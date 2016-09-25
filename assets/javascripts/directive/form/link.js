angular.module('admin42')
    .directive('formLink', [function() {
        return {
            restrict: 'E',
            templateUrl: function(elem, attrs) {
                return attrs.template;
            },
            scope: {
                elementDataId: '@elementDataId'
            },
            controller: ['$scope', 'jsonCache', '$uibModal', '$sce', '$formService', function($scope, jsonCache, $uibModal, $sce, $formService) {
                $scope.formData = jsonCache.get($scope.elementDataId);
                $scope.linkData = $scope.formData.value;

                $scope.getUrl = function() {
                    return $sce.trustAsResourceUrl($scope.linkData.previewUrl);
                }

                if (angular.isDefined($scope.formData.options.formServiceHash)) {
                    $formService.put(
                        $scope.formData.options.formServiceHash,
                        $scope.formData.name,
                        $scope.elementDataId
                    );
                }

                $scope.selectLink = function() {
                    var modalInstance = $uibModal.open({
                        animation: true,
                        templateUrl: 'element/form/link-modal.html',
                        controller: ['$scope', '$uibModalInstance', 'formData', '$http', function ($scope, $uibModalInstance, formData, $http) {
                            $scope.linkData = formData.value;
                            $scope.availableLinkTypes = formData.availableLinkTypes;
                            $scope.includeArray = [];

                            $scope.link = {
                                setValue: function(value) {
                                    $scope.linkData.linkValue = value;
                                },
                                getValue: function(){
                                    return $scope.linkData.linkValue;
                                }
                            };

                            $scope.change = function(){
                                if ($scope.linkData.linkType.length > 0) {
                                    $scope.includeArray = ['link/' + $scope.linkData.linkType + '.html'];

                                    return;
                                }

                                $scope.includeArray = [];
                            };

                            if ($scope.linkData.linkType !== null) {
                                $scope.change();
                            }

                            $scope.ok = function () {
                                if ($scope.linkData.linkValue == null || $scope.linkData.linkType.length == 0) {
                                    $uibModalInstance.close({
                                        linkId: null,
                                        linkDisplayName: null,
                                        linkValue: null,
                                        linkType: null,
                                        previewUrl: null,
                                    });

                                    return;
                                }

                                $http({
                                    method: "POST",
                                    url: formData.saveUrl,
                                    data: {
                                        type: $scope.linkData.linkType,
                                        value: $scope.linkData.linkValue,
                                        id: $scope.linkData.linkId
                                    }
                                })
                                    .success(function (data){
                                        $uibModalInstance.close({
                                            linkId: data.linkId,
                                            linkDisplayName: data.linkDisplayName,
                                            linkValue: $scope.linkData.linkValue,
                                            previewUrl: data.url,
                                            linkType: $scope.linkData.linkType
                                        });
                                    })
                                    .error(function (){
                                        $uibModalInstance.dismiss('cancel');
                                    });

                            };

                            $scope.cancel = function (){
                                $uibModalInstance.dismiss('cancel');
                            };
                        }],
                        size: 'lg',
                        resolve: {
                            formData: function() {
                                return $scope.formData;
                            }
                        }
                    });

                    modalInstance.result.then(function(data) {
                        $scope.linkData.linkId = data.linkId;
                        $scope.linkData.linkDisplayName = data.linkDisplayName;
                        $scope.linkData.linkValue = data.linkValue;
                        $scope.linkData.linkType = data.linkType;
                        $scope.linkData.previewUrl = data.previewUrl;
                        $scope.formData.errors = [];
                    }, function () {

                    });
                };
            }]
        }
    }]);
