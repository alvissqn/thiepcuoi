/*
 * File Javascript quan trọng dùng chung cho tất cả các trang
 */

 /*
  * Phân biệt thiết bị
  */
function device(){
	var width=$(window).width();
	if(width >= 1024){
		return "desktop";
	}else if(width >= 768){
		return "tablet";
	}else if(width >= 128){
		return "mobile";
	}else{
		return null;
	}
}

/*
 * Set Cookie
 */
function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+ d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(name) {
 	var value = "; " + document.cookie;
 	var parts = value.split("; " + name + "=");
 	if (parts.length == 2) return parts.pop().split(";").shift();
}

/*
 * Input label
 */
 inputLabel = {
 	init: function(){
 		$('.input-label').each(function(){
			var hasContent = false;
			var formEl = $(this).find('input, select');
			if( formEl.length == 0 ){
				return;
			}
			if( formEl.val().length == 0 ){
				$(this).find('label').removeClass('input-label-has-content');
			}else{
				$(this).find('label').addClass('input-label-has-content').css({color: ''});
			}
		});
 	},
 	onFocus: function(thisEl){
 		$(thisEl).parent().find('label').addClass('input-label-has-content input-label-focus');
 	},
 	outFocus: function(thisEl){
 		var outerEl = $(thisEl).parent();
		if( $(thisEl).val().length == 0 ){
			outerEl.find('label').removeClass('input-label-has-content');
		}else{
			outerEl.find('label').addClass('input-label-has-content').css({color: ''});
		}
		outerEl.find('label').removeClass('input-label-focus');
 	}
 };

/*
 * Nhập dạng tiền tệ
 */
function inputCurrency(thisEl){
	var val=$(thisEl).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	$(thisEl).val(val);
}

/*
 * Chuyển số sang dạng tiền tệ
 */
function numberFormat(nStr){
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

/*
 * Dịch ngôn ngữ
 */
function __(key, replace = {})
{
	let translation = key.split('.').reduce( function(t, i){
		return typeof t[i] == 'undefined' ? i : t[i];
	}, _lang);
	for (var placeholder in replace) {
		translation = translation.replace(`:${placeholder}`, replace[placeholder]);
	}
	return translation;
}

$(document).ready(function(){
	inputLabel.init(); // Khởi tạo các input
});