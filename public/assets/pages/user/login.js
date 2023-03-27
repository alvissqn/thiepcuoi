/*
 * Ấn nút đăng nhập tài khoản
 */
function userLoginSubmit(thisEl){
	var formEl = $(thisEl).parents('form');
	var btnEl = formEl.find('button');
	formEl.find('.is-invalid').removeClass('is-invalid').removeAttr('data-original-title');
	formEl.find('.form-notify').empty().slideUp();
	btnEl.prop('disabled', true);
	$.ajax({
		url: '/user/login',
		type: 'POST',
		dataType: 'JSON',
		data: formEl.serialize(),
		success: function(res){
			if(typeof res.errors == 'undefined'){
				// Dữ liệu trả về không hợp lệ
				setTimeout(function(){
					userLoginSubmit(thisEl); // Gửi lại khi có lỗi
				}, 2000);
				console.log(res);
			}else if(res.errors.length == 0){
				// Thành công
				alert('Quý khách đã đăng ký tài khoản thành công');
				location.href = "/";
			}else{
				// Lỗi invalid
				$.each(res.errors, function(key, value){
					formEl.find('[name="'+key+'"]').addClass('is-invalid').attr('data-original-title', value[0]).attr('data-toggle', 'tooltip');
					formEl.find('.form-notify').html(value[0]).slideDown();
				});
				$('[data-toggle="tooltip"]').tooltip()
			}
			btnEl.prop('disabled', false);
		},
		error: function(e){
			setTimeout(function(){
				userLoginSubmit(thisEl); // Gửi lại khi có lỗi
			}, 2000);
			console.log(e.responseJSON);
		}
	});
}

/*
 * Ấn enter để gửi
 */
$('#user-login-form').on('keypress', function(e){
	if( e.keyCode == 13 ){
		userLoginSubmit( e.target );
		e.preventDefault();
	}
});

/*
 * Lưu email để lấy lại mật khẩu
 */
$('#user-login-form input[name="email"]').on('keyup', function(e){
	$('#user-login-forgot-password').attr( 'href', 'forgot-password?email='+$(this).val() );
});