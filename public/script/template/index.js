(function() {
	$('.recharge').click(function() {
		$('#auditModal').modal('show');
	})
  $('.showcontent').click(function() {
    $('#content').val('');
    $('#content').val($(this).text())
    $('#detailModal').modal('show');
  })
  $('#close').click(function() {
    $('#detailModal').modal('hide');
  })
  $('#audit').click(function() {
    var params = $('#auditForm').serializeArray();
    console.log(params)
  	$.post('/template/aduit', params, function(res){
  		if (res.status === true) {
        location.reload();
      } else {
        alert(res.msg);
      }
  	})
  })
})()