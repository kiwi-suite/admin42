angular.module('admin42')
    .controller('WysiwygController', function ($scope, $attrs, $http) {
        if ($attrs.ngBaseUrl) {
            tinymce.baseURL = $attrs.ngBaseUrl;
        }

        $scope.tinymceOptionsFull = {
            trusted: true,
            format: 'raw',
            file_browser_callback: fileBrowser,
            plugins: 'paste autolink lists charmap table code link' +
                //'media link42 image42 ' +
            '',
            menubar: false,
            // == http://www.tinymce.com/wiki.php/Controls
            toolbar: 'undo redo paste | styleselect | bold italic | link unlink | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | removeformat | table code | ' +
                //"link42 image42 | " +
            '',
            skin: 'lightgray',
            theme: 'modern',
            elementpath: false,
            convert_urls: false,
            resize: true
        };

        // == http://www.tinymce.com/wiki.php/TinyMCE3x:How-to_implement_a_custom_file_browser
        // == http://michaelbudd.org/tutorials/view/28/building-custom-file-browser-for-tinymce-version-4
        function fileBrowser(field_name, url, type, win) {

            var fileBrowserUrl = $attrs.ngFileBrowser;
            var dialogTitles = angular.fromJson($attrs.ngFileBrowserTitles) || {};

            if (!fileBrowserUrl) {
                alert('File browser URL missing (ng-file-browser)');
                return;
            }

            fileBrowserUrl += '?type=' + type;

            // add currently selected file to query params
            var current = $('#' + field_name).val();
            if (current !== '') {
                fileBrowserUrl += '&current=' + encodeURIComponent(current);
            }

            var dialog = tinymce.activeEditor.windowManager.open({
                file: fileBrowserUrl,
                title: dialogTitles[type] || "File Browser",
                width: 800,
                height: 450,
                resizable: "yes",
                buttons: [
                    // possibility to make selection that has to be confirmed via done button instead of immediately selecting and closing dialog (pull from iframe dialog into inline dialog)
                    {
                        text: 'Done',
                        onclick: function (e) {
                            var frame = $(e.currentTarget).find("iframe").get(0);
                            var content = frame.contentDocument;
                            var selectedItem = $(content).find('input#linkSelection');
                            var linkData = selectedItem.val();
                            if (linkData.length == 0) {
                                top.tinymce.activeEditor.windowManager.close();
                            }

                            linkData = angular.fromJson(linkData);

                            if (angular.isUndefined(linkData.type)) {
                                top.tinymce.activeEditor.windowManager.close();
                            }

                            $http({
                                method: "POST",
                                url: $attrs.ngLinkSaveUrl,
                                data: linkData
                            })
                                .success(function (data){
                                    win.document.getElementById(field_name).value = data.url;
                                    top.tinymce.activeEditor.windowManager.close();
                                })
                                .error(function (){
                                    top.tinymce.activeEditor.windowManager.close();
                                });

                        }
                    },
                    {
                        text: 'Cancel',
                        onclick: function (e) {
                            top.tinymce.activeEditor.windowManager.close();
                        }
                    }
                ]
            }, {
                // adding params just in case
                field_name: field_name,
                type: type,
                win: win
            });
        }
    });
