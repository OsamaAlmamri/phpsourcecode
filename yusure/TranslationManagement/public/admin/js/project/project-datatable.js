var TableDatatablesAjax = function() {
  var datatableAjax = function(){
    dt = $('.dataTablesAjax');
    ajax_datatable = dt.DataTable({
        "processing": true,
        "serverSide": true,
        "searching" : true,
        "searchDelay": 800,
        "search": {
            "regex": true
        },
    "aLengthMenu": [[100, 25, 50], [100, 25, 50]],
      "ajax": {
        'url' : '/admin/project/ajaxIndex',
        'data': function ( data ) {
            data.app_id = $( '#app_id' ).val();
        },
      },
      "pagingType": "full_numbers",
      "orderCellsTop": true,
      "dom" : '<"html5buttons"B>lTfgitp',
      "buttons": [
        {extend: 'copy',title: 'project'},
        {extend: 'csv',title: 'project'},
        {extend: 'excel', title: 'project'},
        {extend: 'pdf', title: 'project'},
        {extend: 'print',
         customize: function (win){
            $(win.document.body).addClass('white-bg');
            $(win.document.body).css('font-size', '10px');
            $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
          }
        }
      ],
      "columns": [
        {
            "data": "id",
            "name" : "id",
        },
        {
            "data": "app_name",
            "name" : "app_name",
            "orderable" : false,
        },
        {
            "data": "name",
            "name" : "name",
            "orderable" : false,
        },
        {
            "data": "description",
            "name": "description",
            "orderable" : false,
        },
        {
          "data": "username",
          "name": "username",
          "orderable" : false,
        },
        { 
            "data": "created_at",
            "name": "created_at",
            "orderable" : true,
        },
        { 
            "data": "updated_at",
            "name": "updated_at",
            "orderable" : true,
        },
        {
          "data": "actionButton",
          "name": "actionButton",
          "type": "html",
          "orderable" : false,
        },
        ],
      "drawCallback": function( settings ) {
        ajax_datatable.$('.tooltips').tooltip( {
          placement : 'top',
          html : true
        });  
      },
      "language": {
        url: '/admin/i18n'
      }
    });
  };
    return {
        init : datatableAjax
    }
}();
$(function () {
  TableDatatablesAjax.init();
});