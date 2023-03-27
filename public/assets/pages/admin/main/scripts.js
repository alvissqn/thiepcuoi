/*
 * Ấn nút xem thông báo
 */
headerNotification = {

	// Click xem thông báo
	show: function(){
		var overlayId = 'header-notifications-overlay';
		if( $('#'+overlayId).length == 0 ){
			$('body').append(`<div class="overlay" id="${overlayId}"></div>`);
			// Click để bên ngoài để đóng
			$('#'+overlayId).click(function(e){
				$(this).hide();
				$('#header-notifications>section').slideUp();
				headerNotification.refresh();
			});
		}
		$('#header-notifications-overlay').toggle();
		var notifyEl = $('#header-notifications>section');
		headerNotification.refresh();
		notifyEl.slideToggle(200);
	},

	// Đánh dấu là đã đọc
	maskAsReaded: function(id){
		$.ajax({
			url: '/user/notification/readed-notification',
			type: 'POST',
			dataType: 'JSON',
			data: {id: id},
			success: function(){
				headerNotification.refresh();
			}
		});
	},

	// Làm mới danh sách
	refresh: function(){
		$.ajax({
			url: '/user/notification/get-my-notification',
			type: 'GET',
			dataType: 'JSON',
			success: function(data){
				if( data.unread > 0 ){
					$('#header-notifications>a>i').addClass('bx-tada');
					$('#header-notifications>a>sub').text( data.unread ).fadeIn();
				}else{
					$('#header-notifications>a>i').removeClass('bx-tada');
					$('#header-notifications>a>sub').fadeOut();
				}
				if( data.items.length > 0 ){
					$('#header-notifications>a').fadeIn();
				}else{
					$('#header-notifications>a').fadeOut();
				}
				data.items.reverse();
				if( typeof _readedCount == 'undefined' ){
					_readedCount = 0;
					_unreadCount = 0;
				}
				$.each(data.items, function(i, item){
					var itemId = 'hnotification-item-'+item.id;
					if( $('#header-notifications').find('#'+itemId).length > 0 ){
						// Tin nhắn này đã load
						return;
					}
					switch( item.type ){
						// Tin nhắn
						case 0:
							var iconLabel = '<span class="badge-circle badge-circle-sm badge-circle-warning font-small-1"><i class="bx bx-envelope"></i></span>';
						break;
						
						// Thông báo
						case 1:
							var iconLabel = '<span class="badge-circle badge-circle-sm badge-circle-info font-small-1"><i class="bx bx-bell"></i></span>';
						break;

					}
					var html = `
						<div class="card collapse-header">
							<div class="card-header collapsed" data-toggle="collapse" data-target="#${itemId}" aria-expanded="false" onclick="headerNotification.maskAsReaded(${item.id})">
								<span class="collapse-title font-small-3">
									<span class="align-middle">
										${iconLabel}
										${item.title}
									</span>
								</span>
							</div>
							<div id="${itemId}" data-parent="#hnotifications-list" class="collapse">
								<div class="card-content">
									<div class="card-body">
										<div>
											${item.content}
										</div>
										<div class="text-right font-small-2 text-gray">
											<i class="bx bx-time"></i>
											${item.created_at_date}
										</div>
									</div>
								</div>
							</div>
						</div>
					`;
					if( item.readed > 0 ){
						_readedCount++;
						$('#header-notifications #header-notifications-readed').prepend( html );
					}else{
						_unreadCount++;
						$('#header-notifications #header-notifications-unread').prepend( html );
					}
				});
				$('#header-notifications a[href="#header-notifications-unread"]>span').text(_unreadCount);
				$('#header-notifications a[href="#header-notifications-readed"]>span').text(_readedCount);
			},
			error: function(){
				setTimeout(function(){
					headerNotification.refresh();
				}, 3000);
			}
		});
	}
}


/*
 * Menu
 */
function adminLeftMin(first){
	if( first && $('.admin-left.admin-min').length == 0 ){
		return;
	}
	$('.admin-left').off('mouseenter mouseleave');
	$('.admin-left').on('mouseenter', function(){
		$(this).removeClass('admin-min');
	}).on('mouseleave', function(){
		$(this).addClass('admin-min');
	});
}

/*
 * Ấn nút cố định menu
 */
function adminLeftPin(){
	if( getCookie('admin_left_menu_min') == 0 ){
		// Mở rộng menu
		setCookie('admin_left_menu_min', 1, 365);
		$(".admin-left, .admin-right").removeClass('admin-min');
		$('.admin-left').off('mouseenter mouseleave');
		$('.admin-collapse>span').text( $('.admin-collapse>span').attr('data-compress') );
		$('.admin-collapse>i').attr('class', 'bx bx-collapse');
	}else{
		// Thu gọn menu
		setCookie('admin_left_menu_min', 0, 365);
		$(".admin-left, .admin-right").addClass('admin-min');
		$('.admin-collapse>span').text( $('.admin-collapse>span').attr('data-expand') );
		$('.admin-collapse>i').attr('class', 'bx bx-expand');
		adminLeftMin(false);
	}
}

//Thu gọn menu
function adminLeftCollapse(){
	$(".admin-left").removeClass("admin-min");
	$(".admin-right").removeClass("admin-min");
	if($(window).width() > 768){
		$(".admin-left .admin-collapse").find("i").toggleClass("fa-chevron-circle-right");
		adminLeftPin();
	}else{
		$(".admin-left").toggle();
		$(".admin-right").toggleClass("width-100");
	}
}

$(document).ready(function(){
	// Load tin nhắn
	headerNotification.refresh();
	setInterval(function(){
		if( document.hasFocus() ){
			headerNotification.refresh();
		}
	}, 30e3); // Load tin nhắn sau mỗi 30 giây

	adminLeftMin(true);
	function showadminBody(gid){
		$(".admin-container,.admin-section").hide();
		//$(".admin-left .admin-actived").removeClass("admin-actived");
		var body=$("#adminContainer-"+gid+"");
		body.show();
		body.parents(".admin-section").show();
		$("html").animate({ scrollTop: $("html").offset().top }, 0);
	}

	//Click hiện nội dung
	$(".admin-left nav,.admin-left ol li").click(function(e){
		if($(this).hasClass("admin-has-sub")){
			//$(".admin-left .admin-has-sub.admin-actived").removeClass("admin-actived");
		}else{
			showadminBody($(this).attr("data-id"));
			if(!$(".admin-left.admin-min").length>0 && $(window).width()<768){
				adminLeftCollapse();
			}
		}
		//$(this).addClass("admin-actived");
	});


	//Click hiện sub menu
	$(".admin-left .admin-has-sub").click(function(e){
		var sub = $(this).next();
		$(".admin-left .admin-has-sub").not( $(this) ).removeClass("admin-arrow-down");
		$(".admin-scrollbar>ol").not( sub ).slideUp(300);
		$(this).toggleClass("admin-arrow-down");
		sub.slideToggle(300);
	});

	// Click avatar
	if(device()=="desktop"){
		$(".admin-header-user").on("mouseenter", function(e){
			$(this).children("nav").slideDown(100);
		});
	}else{
		$(".admin-header-user").on("click", function(e){
			$(this).children("nav").slideToggle(100);
		});
	}
	$(".admin-header").on("mouseleave", function(){
		$(this).find(".admin-header-user>nav").slideUp(100);
	});

	$('.admin-actived').parent().prev('nav').addClass('admin-actived-color');

	// Đếm số thông báo
	var notifyCount = $('#fixed-button-bottom nav a[data-id="notify"] sup').text().replace('(', '').replace(')', '');
	if( notifyCount > 0 ){
		$('.header-notify-icon a[data-id="notify"] sub').text(notifyCount).show();
	}
});