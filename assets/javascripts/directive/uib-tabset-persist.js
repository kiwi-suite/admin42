angular.module('admin42')
    .directive('uibTabsetPersist', function () {
        return {
            require: '^uibTabset',
            link: function (scope, element, attr, ctrl) {
                var nameSpace = attr.uibTabsetPersist;

                if (localStorage.getItem(nameSpace)) {
                    var activeTab = localStorage.getItem(nameSpace);
                    ctrl.select(ctrl.tabs[activeTab])
                }

                ctrl.select = function(selectedTab){
                    ctrl.tabs.forEach(function(tab, index){
                        tab.active = false;
                        if(tab == selectedTab) {
                            tab.active = true;
                            localStorage.setItem(nameSpace, index);
                        }
                    });
                };
            }
        };
    });
