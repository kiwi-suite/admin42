var dth = {
    small: function(data, type, row) {
        if (data === null) {
            return "";
        }
        return '<small>'+data+'</small>';
    },
    id: function(data, type, row) {
        if (data === null) {
            return "";
        }
        return '<code>#'+data+'</code>';
    },
    role: function(data, type, row) {
        return '<span class="label label-info">' + data + '</span>'
    },
    editButton: function(data, type, row) {
        if (data === null || typeof data.type === 'undefinied' || data.type != 'edit') {
            return "";
        }

        return '<a href="'+data.url+'" class="btn btn-default btn-xs">' +
        '<span class="fa fa-edit"></span> Edit' +
        '</a>';
    },

    deleteButton: function(data, type, row) {
        if (data === null || typeof data.type === 'undefinied' || data.type != 'delete') {
            return "";
        }

        return '<a href="'+data.url+'" class="btn btn-danger btn-xs delete-list-link" data-params="'+data.params+'">' +
        '<span class="fa fa-trash-o"></span> Delete' +
        '</a>';
    }
};

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

    $('table.table-datatable').on('click', '.delete-list-link', function(event){
        event.preventDefault();

        var dataTable = $(this).closest('table.table-datatable').dataTable().api();

        $.ajax({
            url: $(this).attr('href'),
            type: 'DELETE',
            cache: false,
            data: $(this).data('params'),
            success: function(response){
                dataTable.ajax.reload(null, false);
            }
        });
    });

    $('.dynamic-form-submit').click(function(event){
        event.preventDefault();

        var form = $('<form />')
            .attr('action', $(this).data('action'))
            .attr('method', 'POST');

        var data = $(this).data();
        $.each(data, function(index, value){
            if (index.length < 5 || index.substring(0, 4) != "form") {
                return;
            }
            var name = index.substring(4).toLowerCase();
            form.append(
                $('<input />').attr('type', 'hidden')
                    .attr('name', name)
                    .attr('value', value)
            );
        });

        $('body').append(form);
        form.submit();
    });
});
