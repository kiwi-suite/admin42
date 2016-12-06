tinymce.PluginManager.add('link42', function(editor, url) {
    var windowWidth = window.innerWidth;
    var windowHeight = window.innerHeight;
    windowWidth = Math.min(Math.round(windowWidth * 0.9), 600);
    windowHeight = Math.round(windowHeight * 0.7);


    // Add a button that opens a window
    editor.addButton('link42', {
        icon: 'link',
        tooltip: 'Insert/edit link',
        onPostRender: function() {
            var ctrl = this;

            editor.on('NodeChange', function(e) {
                ctrl.disabled(!(editor.selection.getContent().length > 0));
            });
        },
        onclick: function() {
            var linkUrl = editor.editorManager.settings.link_url;
            var linkSaveUrl = editor.editorManager.settings.link_save_url;

            var linkId = "";
            var node = editor.selection.getNode();
            if (node.nodeName == "A") {
                var regex = new RegExp('###([0-9]+)###');
                var matched = regex.exec(node.href);
                if (matched !== null && typeof matched[1] !== 'undefined') {
                    linkId = matched[1];
                }
            }

            if (linkId.length > 0) {
                linkUrl += linkId + "/";
            }


            // Open window
            editor.windowManager.open({
                title: 'Link',
                url: linkUrl,
                width: windowWidth,
                height: windowHeight,
                buttons: [
                    {
                        text: 'Save',
                        onclick: function(e) {
                            var frame = $(e.currentTarget).find("iframe").get(0);
                            var content = frame.contentDocument;
                            var selectedItem = $(content).find('input#linkSelection');
                            var linkData = selectedItem.val();
                            linkData = angular.fromJson(linkData);

                            if (angular.isUndefined(linkData.linkType)) {
                                editor.windowManager.close();

                                return;
                            }


                            jQuery.ajax({
                                url: linkSaveUrl,
                                type: "POST",
                                data: angular.toJson({
                                    id: linkData.linkId,
                                    type: linkData.linkType,
                                    value: linkData.linkValue
                                }),
                                contentType: "application/json",
                                error: function(){
                                    editor.windowManager.close();
                                },
                                success: function(data) {
                                    if (angular.isUndefined(data.linkId) || data.linkId.length == 0) {
                                        editor.windowManager.close();

                                        return;
                                    }



                                    editor.undoManager.transact(function() {
                                        editor.execCommand('mceInsertLink', false, {
                                            href: "###" + data.linkId + "###"
                                        });
                                    });
                                    editor.windowManager.close();
                                }
                            });
                        }
                    },
                    {
                        text: 'Close',
                        onclick: 'close'
                    }
                ]
            });
        },
        stateSelector: 'a[href]'
    });

    editor.addButton('unlink42', {
        icon: 'unlink',
        tooltip: 'Remove link',
        cmd: 'unlink',
        stateSelector: 'a[href]'
    });
});
