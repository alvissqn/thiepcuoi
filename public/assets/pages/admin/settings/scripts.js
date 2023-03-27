/*
 * Lưu thiết lập
 */

var _settingsSaveTryAgainDelay = null;
function settingsSave(self, reload){
	var formEl = $(self).parents('form');
	var data = formEl.serializeArray();
	formEl.find('.form-save-btn').prop('disabled', true);
	formEl.find('input[type="checkbox"]:not(:checked)').each( function () {
		if( data.findIndex(x => x.name === $(this).attr('name') ) === -1 ){
			data.push({
				name: $(this).attr('name'),
				value: ''
			});
		}
    });
	$.ajax({
		url: formEl.attr('action'),
		type: 'POST',
		data: data,
		dataType: 'JSON',
		success: function(res){
			if( typeof res.errors == 'undefined' ){
				// Dữ liệu trả về không hợp lệ
				setTimeout(function(){
					settingsSave(self, reload);
				}, 5000);
				toastr.remove();
				toastr.error(__('general.update_error_pending_try_again'), '', {timeOut: 5000, progressBar: !0});
			}else if( res.errors.length == 0 ) {
				// Thành công
				toastr.remove();
				toastr.success(__('general.update_success'), '', {timeOut: 3000});
				if( reload ){
					location.reload();
				}
				formEl.find('.form-save-btn').prop('disabled', false);
			}else{
				// Lỗi invalid
				toastr.remove();
				toastr.error(res.errors[Object.keys(res.errors)[0]], '', {timeOut: 10000, progressBar: !0});
			}
		},
		error: function(e){
			console.log(e);
			clearTimeout(_settingsSaveTryAgainDelay);
			_settingsSaveTryAgainDelay = setTimeout(function(){
				settingsSave(self, reload);
			}, 5000);
			toastr.remove();
			toastr.error(__('general.update_error_pending_try_again'), '', {timeOut: 5000, progressBar: !0});
		}
	});
}

/*$('#settings-form').on('keypress', function(e){
	
});*/