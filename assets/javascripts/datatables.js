(function(window, document, undefined){

    var factory = function( $, DataTable ) {
        "use strict";


        /* Set the defaults for DataTables initialisation */
        $.extend( true, DataTable.defaults, {
            "dom": '<"panel-body"f>t<"panel-body"<"pull-left"l><"pull-right"p><"clearfix">r>',
            "processing": true,
            "language": {
                "search": '<div class="input-group"><span class="input-group-addon"><span class="fa fa-search"></span></span>_INPUT_</div>',
                "lengthMenu": "_MENU_",
                "paginate": {
                    "next" : '<span class="fa fa-angle-right"></span>',
                    "previous" : '<span class="fa fa-angle-left"></span>'
                }
            },
            renderer: 'bootstrap'
        } );


        /* Default class modification */
        $.extend( DataTable.ext.classes, {
            sFilter:  "pull-right",
            sInfo: "pull-left",
            sWrapper:      "dataTables_wrapper form-inline dt-bootstrap",
            sFilterInput:  "form-control input-sm",
            sLengthSelect: "form-control input-sm"
        } );


        /* Bootstrap paging button renderer */
        DataTable.ext.renderer.pageButton.bootstrap = function ( settings, host, idx, buttons, page, pages ) {
            var api     = new DataTable.Api( settings );
            var classes = settings.oClasses;
            var lang    = settings.oLanguage.oPaginate;
            var btnDisplay, btnClass;

            var attach = function( container, buttons ) {
                var i, ien, node, button;
                var clickHandler = function ( e ) {
                    e.preventDefault();
                    if ( e.data.action !== 'ellipsis' ) {
                        api.page( e.data.action ).draw( false );
                    }
                };

                for ( i=0, ien=buttons.length ; i<ien ; i++ ) {
                    button = buttons[i];

                    if ( $.isArray( button ) ) {
                        attach( container, button );
                    }
                    else {
                        btnDisplay = '';
                        btnClass = '';

                        switch ( button ) {
                            case 'ellipsis':
                                btnDisplay = '&hellip;';
                                btnClass = 'disabled';
                                break;

                            case 'first':
                                btnDisplay = lang.sFirst;
                                btnClass = button + (page > 0 ?
                                    '' : ' disabled');
                                break;

                            case 'previous':
                                btnDisplay = lang.sPrevious;
                                btnClass = button + (page > 0 ?
                                    '' : ' disabled');
                                break;

                            case 'next':
                                btnDisplay = lang.sNext;
                                btnClass = button + (page < pages-1 ?
                                    '' : ' disabled');
                                break;

                            case 'last':
                                btnDisplay = lang.sLast;
                                btnClass = button + (page < pages-1 ?
                                    '' : ' disabled');
                                break;

                            default:
                                btnDisplay = button + 1;
                                btnClass = page === button ?
                                    'active' : '';
                                break;
                        }

                        if ( btnDisplay ) {
                            node = $('<li>', {
                                'class': classes.sPageButton+' '+btnClass,
                                'aria-controls': settings.sTableId,
                                'tabindex': settings.iTabIndex,
                                'id': idx === 0 && typeof button === 'string' ?
                                settings.sTableId +'_'+ button :
                                    null
                            } )
                                .append( $('<a>', {
                                    'href': '#'
                                } )
                                    .html( btnDisplay )
                            )
                                .appendTo( container );

                            settings.oApi._fnBindAction(
                                node, {action: button}, clickHandler
                            );
                        }
                    }
                }
            };

            if (pages > 1) {
                attach(
                    $(host).empty().html('<ul class="pagination"/>').children('ul'),
                    buttons
                );
            }else{
                $(host).empty();
            }
        };
    };


// Define as an AMD module if possible
    if ( typeof define === 'function' && define.amd ) {
        define( ['jquery', 'datatables'], factory );
    }
    else if ( typeof exports === 'object' ) {
        // Node/CommonJS
        factory( require('jquery'), require('datatables') );
    }
    else if ( jQuery ) {
        // Otherwise simply initialise as normal, stopping multiple evaluation
        factory( jQuery, jQuery.fn.dataTable );
    }


})(window, document);
