/*
 * Sửa ngôn ngữ nhanh bằng phím Ctrl + Shift
 */

$(document).ready(function(){
	
	var _adminLanguageData = {};
	$(document).on('contextmenu', function(e){
		var mousePosition = {x:0, y:0};
		mousePosition.x = e.clientX;
		mousePosition.y = e.clientY;
		var self = $(':hover');
		var self = self[self.length-1];
		if( $.isEmptyObject(_adminLanguageData) ){
			$('.admin-language-data').each(function(){
				var strData = JSON.parse( $(this).html() );
				_adminLanguageData[strData.key] = strData.value;
			});
		}
		if( $('#admin-edit-language-modal').length == 0 ){
			$('body').append(`
				<section class="custom-scrollbar" id="admin-edit-language-modal">
					<div class="text-center mb-1">
						<button class="btn btn-sm btn-primary" onclick="$('#admin-edit-language-modal').hide()">
							<i class="bx bx-x"></i>
						</button>
					</div>
					<section></section>
				</section>
			`);
		}
		var text = [];
		switch( $(self).prop('tagName') ){
			case 'INPUT':
				text.push( $(self).attr('placeholder') );
				$(self).parent().find('*').each(function(){
					text.push( $(this).text() );
				});
			break;

			case 'SELECT':
				if( $(self).parent().hasClass('input-label') || $(self).hasClass('select2')){
					text.push( $(self).parent().find('span, label').text() );
				}
			break;

			default:
				if( $(self).hasClass('select2-selection__rendered') ){
					$(self).parents('.select2-outer').find('*').each(function(){
						text.push( $(this).text() );
					});
				}else{
					text.push( $(self).text() );
					$(self).parent().find('*').each(function(){
						text.push( $(this).text() );
					});
				}
				
			break;
		}
		var link = '';
		var textFilter = [];
		$.each(text, function(key, value){
			if( typeof value != 'undefined' ){
				var value = value.trim();
				if( value.length > 0 ){
					textFilter.push( value );
				}
			}
		});
		var textFilter = textFilter.filter((v, i, a) => a.indexOf(v) === i);
		$.each(textFilter, function(key, value){
			if(typeof _adminLanguageData[value] != 'undefined'){
				link += `
					<a target="_blank" href="/admin/settings/language?path=${_adminLanguageData[value].split('.')[0]}#field-${_adminLanguageData[value].split('.')[1]}">
						${value}
					</a>
				`;
			}
		});
		if( link.length > 0 ){
			$('#admin-edit-language-modal>section').html(link);
			var posX = mousePosition.x, posY = mousePosition.y;
			posY = posY + 20;
			if( posX > 150){
				posX = posX - 150;
			}
			if( $(window).height() - mousePosition.y < 200 ){
				posY = posY - 180;
			}
			$('#admin-edit-language-modal').show().css({'left': posX, 'top': posY});
		}
		return false;
	});
});