(function() {
  var userid = '';
  var pwd = '';
  // 充值
  $('.recharge').click(function() {
    userid = $(this).attr('data-id');
    $('#rechargeModal').modal('show');
  })
  $('#recharge').click(function() {
    var params = {
      userid: userid,
      recharge: $('input[name=recharge]').val()
    }
    $.post('/user/recharge', params, function(res) {
      if (res.status === true) {
        location.reload();
      } else {
        alert(res.msg);
      }
    })
  })

  // 回退
  $('.rollback').click(function() {
    userid = $(this).attr('data-id');
    $('#rollbackModal').modal('show');
  })
  $('#rollback').click(function() {
    var params = {
      userid: userid,
      reback: $('input[name=rollback]').val()
    }
    $.post('/user/reback', params, function(res) {
      if (res.status === true) {
        location.reload();
      } else {
        alert(res.msg);
      }
    })
  })

  // 重置密码
  $('.reset').click(function() {
    userid = $(this).attr('data-id');
    $('#resetModal').modal('show');
  })
  $('#reset').click(function() {
    var params = {
      userid: userid,
      resetPwd: $('input[name=reset]').val()
    }
    $.post('/user/resetpwd', params, function(res) {
      if (res.status === true) {
        location.reload();
      } else {
        alert(res.msg);
      }
    })
  })

  // 帐号删除
  $('.delete').click(function() {
    userid = $(this).attr('data-id');
    delModalInit();
    $('#deleteModal').modal('show');
  })
  $('#deletePrev').click(function() {
    pwd = $('input[name=surePwd]').val()
    var params = {
      userid: userid,
      surePwd: $('input[name=surePwd]').val()
    }
    $.post('/user/del', params, function(res) {
      if (res.status === true) {
        $('#arrivalRate').text(res.data.arrival_rate);
        delModalFina();
      } else {
        alert(res.msg)
      }
    })
  })
  $('#deleteSure').click(function() {

    var params = {
      surePwd: pwd,
      userid: userid,
      rate: $('#rate').val()
    }
    $.post('/user/del2', params, function(res) {
      if (res.status === true) {
       location.reload()
      } else {
        alert(res.msg)
      }
    })
  })

  // 开户
  $('#open').click(function() {
    $('#openModal').modal('show');
  })
  $('#openBtn').click(function() {
    var params = $('#userForm').serializeArray();
    $.post('/user/insert', params, function(res) {
      if (res.status === true) {
        location.reload();
      } else {
        alert(res.msg);
      }
    })
  })

  // 设置回退地址
  $('.setCallbackUrl').click(function() {
    $('input[name="collbackUrl"]').val('')
    userid = $(this).attr('data-id');
    $('#urlModal').modal('show');
  })
  $('#setUrl').click(function() {
    var params = {
      user_id: userid,
      url: $('input[name="collbackUrl"]').val()
    }
    $.post('/user/seturl', params, function(res) {
      if (res.status === true) {
        window.location.href = '/index/user/index'
      }
    })
  })
})()

function delModalInit() {
  $('input[name=surePwd]').val('');
  $('#rate').val('');
  $('#arrivalRate').text('');
  $('input[name=surePwd]').show();
  $('#deletePrev').show();
  $('#delTitle').text('身份确认')
  $('#wrapper').hide();
  $('#deleteSure').hide();
}
 function delModalFina() {
  $('input[name=surePwd]').hide();
  $('#deletePrev').hide();
  $('#delTitle').text('帐号删除')
  $('#wrapper').show();
  $('#deleteSure').show();
}