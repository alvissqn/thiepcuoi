/*
 * Ấn nút lấy lại mật khẩu
 */
function userForgotPasswordSubmit(thisEl){
	var formEl = $(thisEl).parents('form');
	var data   = formEl.serialize();
	var btnEl  = formEl.find('button');
	formEl.find('.is-invalid').removeClass('is-invalid').removeAttr('data-original-title');
	formEl.find('.form-notify').empty().slideUp();
	formEl.find('input, button').prop('disabled', true);
	$.ajax({
		url: '/user/forgot-password',
		type: 'POST',
		dataType: 'JSON',
		data: data,
		success: function(res){
			if(typeof res.errors == 'undefined'){
				// Dữ liệu trả về không hợp lệ
				setTimeout(function(){
					userForgotPasswordSubmit(thisEl); // Gửi lại khi có lỗi
				}, 2000);
				console.log(res);
			}else if(res.errors.length == 0){
				// Thành công
				formEl.find('.form-notify').removeClass('alert-danger').addClass('alert-success').html(res.data.message).slideDown();
			}else{
				// Lỗi invalid
				$.each(res.errors, function(key, value){
					formEl.find('[name="'+key+'"]').addClass('is-invalid').attr('data-original-title', value[0]).attr('data-toggle', 'tooltip');
					formEl.find('.form-notify').html(value[0]).slideDown();
				});
				$('[data-toggle="tooltip"]').tooltip()
			}
			formEl.find('input, button').prop('disabled', false);
		},
		error: function(e){
			setTimeout(function(){
				userForgotPasswordSubmit(thisEl); // Gửi lại khi có lỗi
			}, 2000);
			console.log(e.responseJSON);
		}
	});
}

/*
 * Ấn enter để gửi
 */
$('#user-forgot-password-form').on('keypress', function(e){
	if( e.keyCode == 13 ){
		userForgotPasswordSubmit( e.target );
		e.preventDefault();
	}
});

if( $('#user-forgot-password-form input[name="email"]').val().length > 2 ){
	userForgotPasswordSubmit( $('#user-forgot-password-form button')[0] );
}
