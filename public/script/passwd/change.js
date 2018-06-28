(function(){
	var $form = $('form');
	var $old_password = $form.find('input[name="old_password"]');
	var $new_password = $form.find('input[name="new_password"]');
	var $confirm_password = $form.find('input[name="confirm_password"]');
	$form.submit(function(){
		var old_password = $.trim($old_password.val());
		var new_password = $.trim($new_password.val());
		var confirm_password = $.trim($confirm_password.val());
		$form.find('.form-group').removeClass('has-error');
		$form.find('.help-block').text('');
		if(!old_password){
			$old_password.parents('.form-group').addClass('has-error');
			$old_password.parents('.form-group').find('.help-block').text('旧密码不能为空！');
		}else if(!new_password){
			$new_password.parents('.form-group').addClass('has-error');
			$new_password.parents('.form-group').find('.help-block').text('新密码不能为空！');
		}else if(new_password!=confirm_password){
			$confirm_password.parents('.form-group').addClass('has-error');
			$confirm_password.parents('.form-group').find('.help-block').text('两次输入的新密码不一致！');
		}else{
			$.post('/passwd/dochange/',{
				old_password:old_password,
				new_password:new_password
			},function(json){
				alert(json['msg']);
				if(json['status']){
					location.reload();
				}
			},'json');
		}
		return false;
	});
	$form.on('focus','input',function(){
		$group = $(this).parents('.form-group');
		$group.removeClass('has-error');
		$group.find('.help-block').text('');
	});
})();