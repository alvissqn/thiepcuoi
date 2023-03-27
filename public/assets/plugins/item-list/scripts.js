/*
 * Tự động load danh sách html
 */
itemList = {
	// Load danh sách
	ajaxCall: null,
	reload: function(config){
		var data = [];
		var isAutoRefresh = typeof config.timer == 'undefined' ? false : true;
		if( config.formFilter.length > 0 ){
			var data = $(config.formFilter).serializeArray();
		}
		$.each(config.data, function(key, value){
			data.push({
				name: key,
				value: value
			});
		});
		if( isAutoRefresh ){
			// Không tự động load khi đang ở trang > 1
			if( $(config.element).find('.pagination .page-item.active>span').text() > 1 || $(config.element).attr('data-loadingbyfilter') == 1 ){
				$(config.element).css({opacity: ''});
				return;
			}
		}else{
			// Làm mờ nền khi load danh sách
			$(config.element).css({opacity: '0.3'}).attr('data-loadingbyfilter', 1);
		}
		var itemActived = $(config.element).find('.item-list-row.item-list-row-active').attr('data-id');
		clearTimeout(itemList.ajaxCall);
		itemList.ajaxCall = setTimeout(function(){
			$.ajax({
				url: '',
				type: 'GET',
				data: data,
				success: function(res){
					var el = $(res).find(config.element);
					if( el.length > 0 ){
						// Load thành công
						var currentItemTotal = $(config.element).find('.pagination-total').val();
						var loadedItemTotal  = $(el).find('.pagination-total').val();
						var refreshContent   = true;
						if( isAutoRefresh ){
							if( currentItemTotal == loadedItemTotal && typeof config.onfocus == 'undefined' || $(config.element).attr('data-loadingbyfilter') == 1 ){
								var refreshContent = false;
							}
						}else{
							$(config.element).removeAttr('data-loadingbyfilter');
						}
						if( refreshContent ){
							$(config.element).html( el.html() );
							$(config.element).find('.item-list-row[data-id="'+itemActived+'"]').addClass('item-list-row-active');
						}
						$(config.element).attr('data-total', loadedItemTotal).css({opacity: ''});
						config.success();
					}else{
						// Dữ liệu trả về không hợp lệ, load lại
						setTimeout(function(){
							itemList.reload(config);
						}, 5000);
					}
				},
				error: function(){
					setTimeout(function(){
						itemList.reload(config);
					}, 5000);
				}
			});
		}, 300);
	},

	// Tự động load
	autoReload: function(config){
		// Tự động load lại số giây đã thiết lập
		setInterval(function(){
			if( document.hasFocus() ){
				delete config.onfocus;
				itemList.reload(config);
			}
		}, config.timer * 1000);

		// Load lại khi trình duyệt chuyển từ tab khác sang
		window.onfocus = function(){
			$(config.element).css({opacity: '0.3'});
			itemList.reload( Object.assign(config, {onfocus: true}) );
		}

		// Thêm class active mỗi khi click
		$(config.element).on('click', '.item-list-row', function(){
			$('.item-list-row-active').removeClass('item-list-row-active');
			$(this).addClass('item-list-row-active');
		});
	},

	// Setup chuyển trang ajax
	pagination: function(config){
		$(config.element).on('click', '.pagination .page-link', function(e){
			var page = $(this).attr('href');
			if( typeof page == 'undefined' ){
				return;
			}
			var page = new URL(page);
			config.data.page = page.searchParams.get('page');
			config.success = function(){
				$('html').animate({
					scrollTop: $(config.element).offset().top - 250
				}, 200);
			}
			itemList.reload(config);
			e.preventDefault();
			return false;
		});
	},

	// Tự động đổ dữ liệu vào form
	formInsertData: function(formEl, thisEl, modalTitle){
		var dataEl = $(thisEl).parents('.item-list-row').find('.item-json-data');
		if( dataEl.length == 0 ){
			// Xóa hết dữ liệu trong form
			$(formEl).find('input, textarea').each(function(){
				switch( $(this).attr('type') ){
					case 'checkbox':
						if( typeof $(this).attr('data-value') == 'undefined' ){
							$(this).prop('checked', false);
						}else{
							$(this).prop('checked', true);
						}
					break;
					default:
						if( typeof $(this).attr('data-value') == 'undefined' ){
							$(this).val('')
						}else{
							$(this).val( $(this).attr('data-value') );
						}
						if( $(this).hasClass('jscolor') ){
							$(this).css({'background-color': $(this).attr('data-value')});
						}
				}
			});
			$(formEl).find('select').each(function(){
				if( typeof $(this).attr('data-value') == 'undefined' ){
					$(this).find('option').prop('selected', false)
				}else{
					$(this).find('option[value="'+$(this).attr('data-value')+'"]').prop('selected', true);
				}
				if( $(this).hasClass('select2') ){
					$(this).select2({width: '100%'}).trigger('change');
				}
			});
		}else{
			// Đổ dữ liệu
			var data = JSON.parse( dataEl.val() );
			$(formEl).find('input, textarea').each(function(){
				if( typeof $(this).attr('name') == 'undefined' ){
					return;
				}
				var value = data[ $(this).attr('name').split('[')[0] ];
				switch( $(this).attr('type') ){
					case 'checkbox':
						if( typeof value == 'string' || typeof value == 'number' ){
							if( value == 1 ){
								$(this).prop('checked', true);
							}else{
								$(this).prop('checked', false);
							}
						}else{
							if( typeof value == 'undefined' || typeof value[ $(this).attr('value') ] == 'undefined' ){
								$(this).prop('checked', false);
							}else{
								$(this).prop('checked', true);
							}
						}
					break;
					default:
						//console.log(value);
						if( value == null ){
							$(this).val('').attr('value', '');
						}else{
							$(this).val( value ).attr('value', value);
						}
				}
				if( $(this).hasClass('jscolor') ){
					$(this).css({'background-color': value});
				}
			});
			$(formEl).find('select').each(function(){
				var value = data[$(this).attr('name')];
				$(this).find('option[value="'+value+'"]').prop('selected', true);
				if( $(this).hasClass('select2') ){
					$(this).select2({width: '100%'}).trigger('change');
				}
			});
		}
		inputLabel.init();
		$(formEl).find('.form-notify').hide();
		$(formEl).find('.is-invalid').removeClass('is-invalid');
		if( $(formEl).hasClass('modal') ){
			$(formEl).modal('show');
			$(formEl).find('.modal-title').text(modalTitle);
		}
	},

	// Xem chi tiết
	showDetail: function(self, modalEl, title){
		var dataEl = $(self).parents('.item-list-row').find('.item-json-data');
		if( dataEl.length == 0 ){
			return;
		}
		var data = JSON.parse( dataEl.val() );
		$(modalEl).find('.modal-title').text(title);
		var content = $(modalEl).find('.modal-body>template').html();
		var content = content.replace(/\$\{(.+?)\}/g, function(match, token) {
			if( data[token] == null ){
				return '';
			}
			return data[token];
		});
		var content = $('<div>'+content+'</div>');
		content.find('select').each(function(){
			$(this).find('option[value="'+$(this).attr('data-selected')+'"]').attr('selected', 'selected');
		});
		$(modalEl).find('.modal-body>section').html( content.html() );
		inputLabel.init();
		$(modalEl).modal('show');
	},

	// Xóa
	delete: function(config){
		$('#loading').show();
		$.ajax({
			url: config.url,
			data: {id: config.id},
			type: 'DELETE',
			success: function(){
				config.success();
			},
			error: function(){
				setTimeout(function(){
					itemList.delete(config);
				}, 2000);
			}
		});
	}
};