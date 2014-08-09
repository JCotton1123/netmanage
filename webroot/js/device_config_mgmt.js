$(document).ready(function(){
  $('#devices').on('click', '.action.add', function(){
    var wrapper = $('#device-tags');
    var input = wrapper.find('select');
    var deviceId = $(this).attr('data-id');
    var deviceName = $(this).attr('data-name');
    wrapper.css('display', 'block')
    wrapper.find('input').prop('readonly', true);
    //input.tagsinput('add', {id: deviceId, text: deviceName});
    input.tagsinput('add', deviceName);
  });
});
