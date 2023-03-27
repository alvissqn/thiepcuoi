/*
 * Ấn nút đăng ký tài khoản
 */
function userRegisterSubmit(thisEl){
	var formEl = $(thisEl).parents('form');
	var btnEl = formEl.find('button');
	formEl.find('.is-invalid').removeClass('is-invalid').removeAttr('data-original-title');
	formEl.find('.form-notify').empty().slideUp();
	btnEl.prop('disabled', true);
	$.ajax({
		url: '/user/register',
		type: 'POST',
		dataType: 'JSON',
		data: formEl.serialize(),
		success: function(res){
			if(typeof res.errors == 'undefined'){
				// Dữ liệu trả về không hợp lệ
				setTimeout(function(){
					userRegisterSubmit(thisEl); // Gửi lại khi có lỗi
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
				if( typeof res.errors.captcha != 'undefined' ){
					// Làm mới captcha
					$('.form-captcha').attr('src', '/captcha.png?refresh=' + (new Date()).getTime() );
					formEl.find('[name="captcha"]').val('');
				}
				$('[data-toggle="tooltip"]').tooltip()
			}
			btnEl.prop('disabled', false);
		},
		error: function(e){
			setTimeout(function(){
				userRegisterSubmit(thisEl); // Gửi lại khi có lỗi
			}, 2000);
			console.log(e.responseJSON);
		}
	});
}

/*
 * Ấn enter để gửi
 */
$('#user-register-form').on('keypress', function(e){
	if( e.keyCode == 13 ){
		userRegisterSubmit( e.target );
		e.preventDefault();
	}
});