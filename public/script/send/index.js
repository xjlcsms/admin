(function(){
  $('input[name="sign"]').bind('input propertychange', function() { 
    var val = $(this).val();
    var content = $('#content').val();
    var end = 0;
    var title = '';

    if (val.length > 8) {
      val = val.substring(0, 8)
      $(this).val(val);
    }

    end = content.indexOf('】');
    if (end > -1) {
      content = content.substring(end + 1);
    }
    title = '【' + val + '】';
    content = title + '' + content;
    showLen(content);
  });

  $('textarea[name="content"]').bind('input propertychange', function() { 
    var content = $(this).val()
    showLen(content);
  });

  // 短信发送方式
  $('input[name="type"]').click(function(){
    if ($(this).val() == 1) {
      $('#autoBox').removeClass('none');
      $('#xinBox').addClass('none');
    } else {
      $('#autoBox').addClass('none');
      $('#xinBox').removeClass('none');
    }
  });

  // 手机号导入方式
  $('input[name="leadtype"]').click(function(){
    if ($(this).val() == 1) {
      $('#auto').removeClass('none');
      $('#manual').addClass('none');
    } else {
      $('#auto').addClass('none');
      $('#manual').removeClass('none');
      $('#smsFile').val('');
      $('#result').addClass('none');
    }
  });

  // 文件上传
  $('#smsFile').change(function() {
    var formdata = new FormData();
    var smsfile = $("#smsFile")[0].files[0];

    formdata.append("smsfile",smsfile);
    $.ajax({
      url: '/index/send/smsfile?taskid=2',
      type: "post",
      data: formdata,
      async: true,
      cache: false,
      contentType: false,
      processData: false,
      success: function(res){
        if (res.status === true) {
          $('#fileName').text(res.data.filename);
          $('#totalNum').text(res.data.total);
          $('#useNum').text(res.data.true);
          $('#errNum').text(res.data.repeat);
          $('#reNum').text(res.data.repeat);
          $('#auto').addClass('none');
          $('#result').removeClass('none');
        } else {
          alert(res.msg)
        }
      },
      error: function(err){
        console.log(err);
      }
    });
  });

  // 删除文件
  $('#delSmsFile').click(function() {
    var params = {
      fileName: $('#fileName').text()
    }
    $.post('/index/send/delsmsf', params, function(res) {
      if (res.status === true) {
        $('#smsFile').val('');
        $('#result').addClass('none');
        $('#auto').removeClass('none');
      } else {
        alert(res.msg);
      }
    })
  });

  $('#sendBtn').click(function() {
    if ($('input[name="type"]:checked').val() == 1) {
        // 自行发送
      var params = {
        type: $('input[name="type"]:checked').val(),
        tempId: 0,
        smstype: $('input[name="leadtype"]:checked').val(),
        content: $('#content').val(),
        sign: $('#sign').val(),
        smsfile: $('#fileName').text(),
        mobiles: $('#phoneText').val()
      }
      $.post('/index/send/sms', params, function(res) {
        if (res.status === true) {
          alert(res.msg);
          window.location.href="/index/send/sendtask";
        }
      })
    } else {
      // 信嘉联创代发
      var params = {
        type: $('input[name="type"]:checked').val(),
        tempId: 0,
        area: $('input[name="area"]').val(),
        amount: $('input[name="quantity"]').val(),
        content: $('#content').val(),
        sign: $('#sign').val(),
      }
      $.post('/index/send/syssms', params, function(res) {
        if (res.status === true) {
          alert(res.msg);
          window.location.href="/index/send/sendtask";
        }
      })
    }
  })
})()

function showLen(content) {
  var len = 0;
  var branch = 1;

  len = content.length;
  if (len >= 498 ) {
    content = content.substring(0,500);
  }
  len = content.length;
  if (len <= 70) {
  } else {
    if ((len - 70) % 68 == 0) {
      branch += (len - 70)/68;
    } else {
      branch += (len - 70)/68 + 1
    }
  }
  $('#content').val(content);
  $('#num').text(len);
  $('#branch').text(parseInt(branch));
}