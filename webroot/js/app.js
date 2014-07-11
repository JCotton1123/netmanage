$(document).ready(function() {

  Mousetrap.bind('s',function(){
    var search = '<div id="search" class="modal fade" role="dialog">';
    search += '<div class="modal-dialog modal-sm">';
    search += '<div class="modal-content">';
    search += '</div></div></div>';
    $('#search').remove();
    $('body').append(search);
    $('#search').modal({
      remote: '/dashboard/search'
    }).on('shown.bs.modal', function (e) {
     $('#search input').focus();
    });
  });

  $('table.table-refresh').each(function(){
    var tbody = $(this).find('tbody');
    var url = $(this).attr('data-remote');
    var refresh = $(this).attr('data-refresh');
    var filter = $(this).attr('data-filter');
    (function poll(){
      $.ajax({
        url: url,
        data: {
          'filter': filter,
          'mtimestamp': Date.now(),
        },
        dataType: "html",
        timeout: refresh,
        success: function(data){
          $(tbody).html(data);
          setTimeout(
            poll,
            refresh
          )
        }
      });
    })(); 
  });

  $('.vis-graph').each(function(){
    var nodes = JSON.parse($(this).attr('data-nodes'));
    var edges = JSON.parse($(this).attr('data-edges'));
    var options = JSON.parse($(this).attr('data-options'));
    var data = {nodes:nodes, edges:edges};
    var graph = new vis.Graph(this, data, options);
  });
});
