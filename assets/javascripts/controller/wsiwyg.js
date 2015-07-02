angular.module('admin42')
    .controller('WysiwygController', function ($scope, $attrs, $rootScope) {
        if ($attrs.ngBaseUrl) {
            tinymce.baseURL = $attrs.ngBaseUrl;
        }

        $rootScope.$on('$includeContentLoaded', function(event) {});

        $scope.tinymceOptionsFull = {

            // ui-tinymce specific options to avoid $sce strict sanitization (trusted, format)
            trusted: true,
            format: 'raw',

            //content_css: $attrs.ngContentCss,
            file_browser_callback: fileBrowser,

            // == http://www.tinymce.com/wiki.php/Plugins
            plugins: 'paste advlist autolink lists charmap print preview ' +
                //'media link42 image42 ' +
            '',

            // == plugins settings
            image_advtab: true,
            // prefill a link list
            //link_list: [
            //    {title: 'My page 1', value: 'http://www.tinymce.com'},
            //    {title: 'My page 2', value: 'http://www.moxiecode.com'}
            //],
            // classes for links
            //link_class_list: [
            //    {title: 'None', value: ''},
            //    {title: 'Dog', value: 'dog'},
            //    {title: 'Cat', value: 'cat'}
            //],

            // == http://www.tinymce.com/wiki.php/Configuration:menu
            menubar: false,

            // == http://www.tinymce.com/wiki.php/Controls
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | removeformat ' +
                //"link42 image42 | " +
            '',

            skin: 'lightgray',
            theme: 'modern'
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

            // == possible types:
            // file: called from link plugin
            // image: called from image plugin
            // media: called from media plugin
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
                inline: "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
                close_previous: "yes",
                buttons: [
                    // possibility to make selection that has to be confirmed via done button instead of immediately selecting and closing dialog (pull from iframe dialog into inline dialog)
                    //{
                    //    text: 'Done',
                    //    onclick: function (e) {
                    //        var frame = $(e.currentTarget).find("iframe").get(0);
                    //        var content = frame.contentDocument;
                    //        var selectedItem = $(content).find('a.selected');
                    //        handleSelection(selectedItem);
                    //    }
                    //}
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
                win: win,
                handleSelection: handleSelection
            });

            // listen for selection in dialog (pull from iframe dialog into inline dialog)
            $('#' + dialog._id).find('iframe').load(function () {
                var dialog = $($(this).get(0).contentDocument);
                $('a.selectable', dialog).click(function (e) {
                    e.preventDefault();
                    handleSelection($(this));
                });
            });

            // add to dialog html if wanted other way around (push from iframe dialog to inline dialog):
            //$(function () {
            //    $('#assetResults img').live('click', function (event) {
            //        var args = top.tinymce.activeEditor.windowManager.getParams();
            //        win = (args.window);
            //        input = (args.input);
            //        win.document.getElementById(input).value = '/assets/clientAssets/' + $(this).data('filename');
            //        top.tinymce.activeEditor.windowManager.close();
            //    });
            //});

            function handleSelection($selectedItem) {

                var data = {};

                // both images and links
                data.url = $selectedItem.find('img').attr('src') || $selectedItem.attr('href');
                data.url = data.url.trim();
                data.title = $selectedItem.find('img').attr('alt') || $selectedItem.attr('title');
                data.title = data.title.trim();

                var $urlInput = $('#' + field_name);
                $urlInput.val(data.url);

                switch (type) {
                    case 'file':
                        // called from link plugin
                        data.text = data.text || $selectedItem.text();
                        data.text = data.text.trim();
                        data.class = 'site-link';

                        // Text to display
                        var $displayTextInput = $urlInput.parents('.mce-formitem').next().find('input');
                        if ($displayTextInput.val() === '') {
                            $displayTextInput.val(data.text);
                        }

                        // Title
                        var $titleTextInput = $displayTextInput.parents('.mce-formitem').next().find('input');
                        if ($titleTextInput.val() === '') {
                            $titleTextInput.val(data.title);
                        }

                        break;
                    case 'image':
                        // called from image plugin

                        // Image Description
                        var $imageDescritpionInput = $urlInput.parents('.mce-formitem').next().find('input');
                        if ($imageDescritpionInput.val() === '') {
                            $imageDescritpionInput.val(data.title);
                        }

                        // TODO: clear dimensions?

                        break;
                    case 'media':
                        // called from media plugin
                        break;
                }

                console.log(type, data);

                top.tinymce.activeEditor.windowManager.close();
            }

            return false;
        }
    });
