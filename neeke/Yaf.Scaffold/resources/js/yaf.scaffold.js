/**
 * @author ciogao@gmail.com
 */
var primary = $CONFIG.scaffold.primary;
var columns = $CONFIG.scaffold.columns;
var tmp_action = {
    title: "Action", sortable: false, callback: function () {
        return "<a class=\"btn btn-success btn-view\" href=\"javascript:;\"><i class=\"icon-zoom-in icon-white\"></i>View</a> " +
            "<a class=\"btn btn-info btn-edit\" href=\"javascript:;\"><i class=\"icon-edit icon-white\"></i>Edit</a> " +
            "<a class=\"btn btn-danger btn-remove\" href=\"javascript:;\"><i class=\"icon-trash icon-white\"></i>Delete</a>"
    }
};

columns['Action'] = tmp_action;

$("#table-scaffold").datatable({
    title: '',
    perPage: 10,
    url: $CONFIG.scaffold.api.scaffoldajax,
    allowSaveColumns: true,
    showPagination: true,
    showFilter: true,
    filterModal: $("#table-scaffold-filter"),
    rowCallback: function($row,rowdata){
        return $row.attr('data-primary-value',rowdata[primary]);
    },
    columns: columns
});

$('.btn-view').click(function (e) {
    e.preventDefault();
    $('#scaffoldEdit').modal('show');
});

$('.btn-new').click(function (e) {
    e.preventDefault();
    $('#scaffoldEdit').modal('show');
    $("#form-modify").resetForm();
});

$('.btn-edit').live('click', function (e) {
    e.preventDefault();
    $('#scaffoldEdit').modal('show');
    var api_get = $CONFIG.scaffold.api.getrow;
    var primary_value = $(this).closest('tr').attr('data-primary-value');

    $.get(api_get, {primary: primary_value}, function (result) {
        if (result.code == 1000) {
            $.each(result.data, function (key, value) {
                $('input[name="' + key + '"]').val(value);
                $('textarea[name="' + key + '"]').val(value);
            });
        } else {
            alert(result.msg);
        }
    });
})

$('.btn-remove').live('click', function (e) {
    e.preventDefault();
    var this_btn = $(this);
    var api = $CONFIG.scaffold.api.remove;
    var primary_value = this_btn.closest('tr').attr('data-primary-value');
    $.post(api, {primary: primary_value}, function (result) {
        if (result.code && result.code == 1000) {
            this_btn.closest('tr').remove();
        } else {
            alert(result.msg);
        }
    });
})

$('.btn-modify').click(function (e) {
    e.preventDefault();
    var api_modify = $CONFIG.scaffold.api.modify;

    $.ajax({
        type: "POST",
        url: api_modify,
        data: $("#form-modify").serialize(),
        success: function (result) {
            alert(result.msg);
            $('#scaffoldEdit').modal('hide');
        }
    });
});

$('.btn-close').click(function (e) {
    e.preventDefault();
    $(this).parent().parent().parent().fadeOut();
});
$('.btn-minimize').click(function (e) {
    e.preventDefault();
    var $target = $(this).parent().parent().next('.box-content');
    if ($target.is(':visible')) $('i', $(this)).removeClass('icon-chevron-up').addClass('icon-chevron-down');
    else                       $('i', $(this)).removeClass('icon-chevron-down').addClass('icon-chevron-up');
    $target.slideToggle();
});
$('.btn-setting').click(function (e) {
    e.preventDefault();
    $('#myModal').modal('show');
});
