$(document).ready(function() {
    $('table.datatable').each(function(){
        var dt = $(this).dataTable({
            "sPaginationType": "simple",
            "aLengthMenu": [[2, 5, 10, 50, 100, -1], [2, 5, 10, 50, 100, "All"]],
            "iDisplayLength": parseInt($(this).attr('data-length')) || 10,
            "oLanguage": {
              "sEmptyTable": $(this).attr("data-empty-table") === undefined ? "No data available": $(this).attr("data-empty-table"),
              "sProcessing":"<img class='loading' src='/img/loading.gif' />",
            },
            "bAutoWidth": $(this).attr('data-auto-width') === undefined ? false: ($(this).attr('data-auto-width') == 'true'),
            "bPaginate": $(this).attr('data-paginate') === undefined ? true: ($(this).attr('data-paginate') == 'true'),
            "bFilter": $(this).attr('data-search') === undefined ? false: ($(this).attr('data-search') == 'true'),
            "bProcessing": $(this).attr('data-processing') === undefined ? false: ($(this).attr('data-processing') == 'true'),
            "bServerSide": ($(this).attr("data-src") === undefined || $(this).attr('data-src') == 'false' ? false: true),
            "sAjaxSource": ($(this).attr("data-src") === undefined || $(this).attr("data-src") == 'false' ? null: $(this).attr("data-src")),
        });
        if($(this).attr("data-editable") && $(this).attr("data-editable") == 'true'){
          dt.makeEditable({
            "sUpdateURL": $(this).attr("data-update-url")
          });
        }
    });
});
