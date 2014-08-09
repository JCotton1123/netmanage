$(document).ready(function() {

  //Search hotkey
  Mousetrap.bind('s',function(){
    var search = '<div id="search" class="modal fade" role="dialog">';
    search += '<div class="modal-dialog modal-sm">';
    search += '<div class="modal-content">';
    search += '</div></div></div>';
    $('#search').remove();
    $('body').append(search);
    $('#search').modal({
      remote: '/search'
    }).on('shown.bs.modal', function (e) {
     $('#search input').focus();
    });
  });

  //Streaming table
  $('table.table-refresh').each(function(){
    var tbody = $(this).find('tbody');
    var url = $(this).attr('data-remote');
    var refresh = $(this).attr('data-refresh');
    var length = $(this).attr('data-length') || 10;
    (function poll(){
      $.ajax({
        url: url,
        data: {
          'length': length,
          'timestamp': (Math.round(+new Date()/1000)) - refresh,
        },
        dataType: "html",
        timeout: (refresh * 1000),
        success: function(data){
          $(tbody).html(data);
          setTimeout(
            poll,
            (refresh * 1000)
          )
        }
      });
    })(); 
  });

  //Vis network graph
  $('.vis-graph').each(function(){
    var nodes = JSON.parse($(this).attr('data-nodes'));
    var edges = JSON.parse($(this).attr('data-edges'));
    var options = JSON.parse($(this).attr('data-options'));
    var data = {nodes:nodes, edges:edges};
    var graph = new vis.Network(this, data, options);
  });

  //Raphael piechart
  $('.piechart').each(function() {
    //Raphael is kind of lame in that you must supply an
    //id (or position) to the library. Maybe there is a 
    //better way to do this IDK about :(
    if($(this).attr('id') === undefined){
      console.log('You must assign a unique id to your pie chart');
      return;
    }
    var r = Raphael($(this).attr('id'));
    var top = parseInt($(this).attr('data-center-top'));
    var left = parseInt($(this).attr('data-center-left'));
    var radius = parseInt($(this).attr('data-radius'));
    var data = JSON.parse($(this).attr('data-data'));
    var options = JSON.parse($(this).attr('data-options'));
    r.piechart(top, left, radius, data, options);
  });
 
});
