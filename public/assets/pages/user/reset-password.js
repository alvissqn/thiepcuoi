/*
 * Ấn nút lấy lại mật khẩu
 */
function userResetPasswordSubmit(thisEl){
	var formEl = $(thisEl).parents('form');
	var data   = formEl.serialize();
	var btnEl  = formEl.find('button');
	formEl.find('.is-invalid').removeClass('is-invalid').removeAttr('data-original-title');
	formEl.find('.form-notify').empty().slideUp();
	formEl.find('input, button').prop('disabled', true);
	$.ajax({
		url: '/user/forgot-password/reset-password',
		type: 'POST',
		dataType: 'JSON',
		data: data,
		success: function(res){
			if(typeof res.errors == 'undefined'){
				// Dữ liệu trả về không hợp lệ
				setTimeout(function(){
					userResetPasswordSubmit(thisEl); // Gửi lại khi có lỗi
				}, 2000);
				console.log(res);
			}else if(res.errors.length == 0){
				// Thành công
				location.href = res.data.redirect_url;
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
				userResetPasswordSubmit(thisEl); // Gửi lại khi có lỗi
			}, 2000);
			console.log(e.responseJSON);
		}
	});
}

/*
 * Ấn enter để gửi
 */
$('#user-reset-password-form').on('keypress', function(e){
	if( e.keyCode == 13 ){
		userResetPasswordSubmit( e.target );
		e.preventDefault();
	}
});