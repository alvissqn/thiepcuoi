/*
 * Lưu dữ liệu form
 */

formAjaxSave = {
	// Gửi dữ liệu
	send: function(config){
		var formEl = $(config.element);
		var data = new FormData(formEl[0]);
		var btnEl = formEl.find('.form-save');
		formEl.find('.is-invalid').removeClass('is-invalid').removeAttr('data-original-title');
		formEl.find('.form-notify').empty().slideUp();
		btnEl.prop('disabled', true);
		$.ajax({
			url: formEl.attr('action'),
			type: formEl.attr('method'),
			dataType: 'JSON',
			data: data,
			processData: false,
			contentType: false,
			success: function(res){
				if(typeof res.errors == 'undefined'){
					// Dữ liệu trả về không hợp lệ
					setTimeout(function(){
						formAjaxSave.send(config); // Gửi lại khi có lỗi
					}, 2000);
					console.log(res);
				}else if(res.errors.length == 0){
					// Thành công
					config.success();
				}else{
					// Lỗi invalid
					$.each(res.errors, function(key, value){
						formEl.find('[name="'+key+'"]').addClass('is-invalid');
						formEl.find('.form-notify').html(value[0]).slideDown();
					});
				}
				btnEl.prop('disabled', false);
			},
			error: function(e){
				setTimeout(function(){
					formAjaxSave.send(config); // Gửi lại khi có lỗi
				}, 2000);
				console.log(e.responseJSON);
			}
		});
	},

	// Khởi tạo
	init: function(config){
		/*
		 * Ấn enter để gửi
		 */
		$(config.element).on('keypress', 'input', function(e){
			if( e.keyCode == 13 ){
				formAjaxSave.send(config);
				e.preventDefault();
			}
		});
		/*
		 * Khởi tạo
		 */
		$(config.element).on('click', '.form-save', function(e){
			formAjaxSave.send( config );
			e.preventDefault();
		});
	}
};