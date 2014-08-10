$(document).ready(function(){
    $('#btn-sidebar-hide').click(function(event){
        $(this).parent().addClass('hide-sidebar');
        $('#btn-sidebar-show').parent().addClass('hide-sidebar');

        $('.main', $(this).parent().parent())
            .removeClass('col-md-10')
            .removeClass('col-md-offset-2')
            .addClass('col-md-12');
    });

    $('#btn-sidebar-show').click(function(event){
        $(this).parent().removeClass('hide-sidebar');
        $('#btn-sidebar-hide').parent().removeClass('hide-sidebar');

        $('.main', $(this).parent().parent())
            .removeClass('col-md-12')
            .addClass('col-md-10')
            .addClass('col-md-offset-2');
    });
});
