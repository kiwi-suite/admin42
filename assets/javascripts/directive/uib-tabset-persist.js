angular.module('admin42')
    .directive('uibTabsetPersist', function () {
        return {
            require: '^uibTabset',
            link: function (scope, element, attr, ctrl) {
                var nameSpace = 'uibTabsetPersist';
                var id = attr.uibTabsetPersist;

                var currentItem = JSON.parse(localStorage.getItem(nameSpace));

                if (currentItem && currentItem.id == id) {
                    //ctrl.select(ctrl.tabs[currentItem.index])
                }

                ctrl.select = function (selectedTab) {
                    console.log(ctrl);
                    /*ctrl.tabs.forEach(function (tab, index) {
                        tab.active = false;
                        if (tab == selectedTab) {
                            tab.active = true;
                            localStorage.setItem(nameSpace, JSON.stringify({id: id, index: index}));
                        }
                    });*/
                };
            }
        };
    });
