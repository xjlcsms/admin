$(function(){

  // 登录
  $('#login').click(function() {
    var params = {
      username: $('#username').val(),
      password: $('#password').val(),
      isremember: $('#isremember').prop("checked") ? 1 : 0
    }
    //登陆
    $.get('/login/i', params, function(data){
      if (data.status === true) {
        window.location.href = '/'
      } else {
        alert(data.msg)
      }
    })
  })

  // 修改密码
  $('#pwdModalBtn').click(function() {
    $('#editPwdForm')[0].reset()
    $('#pwdModal').modal('show')
  });
  $('#exitPwdBtn').click(function() {
    let params = $('#editPwdForm').serializeArray()
    $.post('/index/index/changepwd', params, function(data) {
      if (data.status === true) {
        window.location.href = '/logout'
      } else {
        alert(data.msg)
      }
    })
  })
})