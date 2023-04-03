@php
	use \App\Services\NotificationServices;
	$splitPath = explode('/', Request::path() );
	$pageParams = [
		'name' => $splitPath[1] ?? null,
		'sname' => $splitPath[2] ?? null,
	];
	if( empty($pageParams['sname']) ){
		$config = config('admin_menu_link')[ $pageParams['name'] ] ?? [];
	}else{
		$config = config('admin_menu_link')[ $pageParams['name'] ]['subs'][ $pageParams['sname'] ] ?? [];
	}
	$config['load_js_language'] = array_merge(
		$config['load_js_language'],
		['general']
	);
	Permission::required($config['permission'], '/');

@endphp
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
	<head>
		@section('header')
			<title>@yield('title', $config['label'] )</title>
			<link href="/assets/pages/admin/main/style.css" rel="stylesheet" type="text/css">
			@include('layouts.includes.header-assets')
			<script src="/assets/pages/admin/main/scripts.js"></script>
		@show
		@section('header-tag')
	    @show
	</head>

	<body>
		<div id="loading" class="hide">
			<div>
				<div>
					<i class="bx bx-loader bx-spin bx-flip-vertical text-white font-large-3"></i>
				</div>
			</div>
		</div>
		<main class="admin admin-theme-{{ Option::get('settings__admin_theme', 'light') }}">
			<section class="admin-header row no-gutters align-items-center">
				<div class="admin-header-left">
					<div class="row no-gutters align-items-center">
						<a class="admin-collapse-icon text-center d-lg-none d-xl-none" onclick="adminLeftCollapse()" style="width: 50px">
							<i class="bx bx-menu"></i>
						</a>
						<a class="logo-outer" href="/" style="width: calc(100% - 50px)">
							<img class="logo" src="{{ Option::get('logo', 'https://trangweb.com/files/uploads/2019/12/Logo-TrangWeb-Small.png') }}" alt="Logo">
						</a>
						<a class="admin-collapse-icon text-center d-none d-xl-block d-lg-block" onclick="adminLeftCollapse()" style="width: 50px">
							<i class="bx bx-menu"></i>
						</a>
					</div>
				</div>
				<div class="col-10">
					<div class="row no-gutters align-items-center justify-content-end">
						<div id="header-notifications">
							<a class="link hide" onclick="headerNotification.show(this)">
								<i class="bx bx-bell"></i>
								<sub style="display: none"></sub>
							</a>
							<section class="accordion collapse-icon accordion-icon-rotate collapse-borderless" id="hnotifications-list">
								<div class="p-1 bg-primary text-white row align-items-center no-gutters">
									<div class="col-5 text-ellipsis">
										{{ __('users/notifications.header_notifications_heading_title') }}
									</div>
									<div class="col-7 text-right link text-ellipsis" onclick="headerNotification.maskAsReaded(0); headerNotification.show()">
										<i class="bx bx-check-double"></i>
										{{ __('users/notifications.header_notifications_heading_mask_as_readed') }}
									</div>
								</div>
								<ul class="nav nav-tabs no-gutters">
									<li class="nav-item col-6">
										<a class="nav-link active font-small-3" data-toggle="tab" href="#header-notifications-unread">
											<i class="bx bx-bell align-top"></i>
											{{ __('users/notifications.header_notifications_heading_unread') }} (<span></span>)
										</a>
									</li>
									<li class="nav-item col-6">
										<a class="nav-link font-small-3" data-toggle="tab" href="#header-notifications-readed">
											<i class="bx bx-list-check align-top"></i>
											{{ __('users/notifications.header_notifications_heading_readed') }} (<span></span>)
										</a>
									</li>
								</ul>
								<div class="header-notifications-body custom-scrollbar" style="max-height: 350px; overflow: auto;">
									<div class="tab-content">

										<!-- Chưa đọc -->
										<div class="tab-pane active" id="header-notifications-unread">

										</div>
										<!-- /Chưa đọc -->

										<!-- Đã đọc -->
										<div class="tab-pane" id="header-notifications-readed">
										</div>
										<!-- / Đã đọc -->

									</div>
								</div>
								<div class="p-1 text-center bg-white">
									<a href="{{ route('admin.account.notifications') }}">
										{{ __('users/notifications.header_notifications_see_all') }}
									</a>
								</div>
							</section>
						</div>
						<div class="admin-header-user">
							<span style="padding: 10px">
								<span class="avatar">
									<img src="{!! \App\Services\UserServices::avatar( Auth::user()->email ) !!}" style="width: 32px; height: 32px">
									<span class="avatar-status-online"></span>
								</span>
								<span class="d-none d-sm-inline-block d-md-inline-block d-lg-inline-block d-xl-inline-block">{{ Auth::user()->name }}</span>
								<i class="bx bx-chevron-down"></i>
							</span>
							<nav>
									<a href="{{ route('admin.account.profile') }}">
										<i class="bx bx-user"></i>
										{{ __('user/general.profile') }}
									</a>
									<a href="{{ route('admin.account.change-password') }}">
										<i class="bx bx-lock"></i>
										{{ __('user/general.change_password') }}
									</a>
									<a href="{{ route('admin.account.change-avatar') }}">
										<i class="bx bx-image"></i>
										{{ __('user/general.change_avatar') }}
									</a>
									<a href="{{ route('admin.account.logs') }}">
										<i class="bx bx-history"></i>
										{{ __('user/general.logs') }}
									</a>
									@if( request()->cookie('auth_private_key_admin') )
										@php
											$adminUserId = explode('.', request()->cookie('auth_private_key_admin') )[0];
										@endphp
										<a href="{{ route('user.logout') }}">
											<i class="bx bx-log-in"></i>
											{{ \App\Services\UserServices::get($adminUserId, 'name') }}
										</a>
									@else
										<a onclick="return confirm('{{ __('user/general.logout_confirm') }}');" href="{{ route('user.logout') }}">
											<i class="bx bx-log-out"></i>
											{{ __('user/general.logout') }}
										</a>
									@endif
							</nav>
						</div>
					</div>
				</div>
			</section>

			<!--Menu bên trái-->
			<section class="admin-left {{ ( empty($_COOKIE['admin_left_menu_min'] ?? true) ? 'admin-min' : '') }}">
				<div class="admin-scrollbar">
					@foreach( config('admin_menu_link') as $url => $item)
						@if( $item['hidden'] || !Permission::has($item['permission']) )
							@continue
						@endif
						@php
							$itemName = $item['label'];
						@endphp
						{{-- Menu bình thường --}}

						@if( empty($item['subs']) )

							<a href="{{ strpos($url, '://') === false ? '/admin/'.$url : $url }}" class="{!! ( ($pageParams['name'] ?? null) == $url ? 'admin-actived' : '') !!}">
								<i class="bx {{ $item['icon'] }}"></i>
								<span>
									{{ $itemName }}
									@if( ($item['count'] ?? 0) > 0 )
										<small class="badge badge-pill badge-{{ $item['count_style'] }} float-right mr-1">
											{{ $item['count'] }}
										</small>
									@endif
								</span>
							</a>
						@else
							{{-- Menu có sub --}}
							<nav class="admin-has-sub admin-arrow-right {!! ( ($pageParams['name'] ?? null) == $url ? 'admin-arrow-down' : '') !!}">
								<i class="bx {{ $item['icon'] }}"></i>
								<span>
									{{ $itemName }}
									@if( ($item['count'] ?? 0) > 0 )
										<small class="badge badge-pill badge-{{ $item['count_style'] }} float-right mr-1">
											{{ $item['count'] }}
										</small>
									@endif
								</span>
							</nav>
							<ol style="{!! ( ($pageParams['name'] ?? null) == $url ? 'display: block' : '') !!}">
								@foreach( $item['subs'] as $surl => $sitem)

									@if( $sitem['hidden'] || !Permission::has($sitem['permission']) )
										@continue
									@endif
									@php
										$itemName = vnStrFilter($url.'_'.$surl, '_');
										$itemName = __('admin/menu_link.'.$itemName);
									@endphp
									<li>
										<a href="{{ strpos($surl, '://') === false ? '/admin/'.$url.'/'.$surl.'' : $surl }}" class="{!! ( ($pageParams['name'] ?? null) == $url && ($pageParams['sname'] ?? null) == $surl ? 'admin-actived' : '') !!}">
											<i class="bx bx-right-arrow-alt"></i>
											<span class="menu-item">
												{{ $sitem['label'] }}
											</span>
											@if( ($sitem['count'] ?? 0) > 0 )
												<span class="badge badge-pill badge-{{ $sitem['count_style'] }} float-right">
													{{ $sitem['count'] }}
												</span>
											@endif
										</a>
									</li>
								@endforeach
							</ol>
						@endif
					@endforeach

					@if( empty($_COOKIE['admin_left_menu_min'] ?? true) )
						<div class="admin-collapse d-none d-lg-block d-xl-block" onclick="adminLeftPin()">
							<i class="bx bx-expand"></i>
							<span data-expand="{{ __('admin/general.menu_expand') }}" data-compress="{{ __('admin/general.menu_compress') }}">
								{{ __('admin/general.menu_expand') }}
							</span>
						</div>
					@else
						<div class="admin-collapse d-none d-lg-block d-xl-block" onclick="adminLeftPin()">
							<i class="bx bx-collapse"></i>
							<span data-expand="{{ __('admin/general.menu_expand') }}" data-compress="{{ __('admin/general.menu_compress') }}">
								{{ __('admin/general.menu_compress') }}
							</span>
						</div>
					@endif
					<div class="admin-collapse d-lg-none d-xl-none" onclick="adminLeftCollapse()">
						<i class="bx bx-collapse"></i>
						<span data-expand="{{ __('admin/general.menu_expand') }}" data-compress="{{ __('admin/general.menu_compress') }}">
							{{ __('admin/general.menu_compress') }}
						</span>
					</div>
				</div>
			</section>
			<!--/Menu bên trái-->

			<!--Nội dung bên phải-->
			<section class="admin-right {{ ( empty($_COOKIE['admin_left_menu_min'] ?? true) ? 'admin-min' : '') }}">
				@yield('content')
			</section>
			<!--/Nội dung bên phải-->
		</main>
		<div class="clear"></div>
		@section('footer')
			<div class="px-2 py-1 text-right">
				<a class="badge-circle badge-circle-info badge-circle-lg" href="#">
					<i class="bx bx-chevron-up"></i>
				</a>
			</div>
			<footer class="footer footer-static footer-light text-right p-2">
				<p>
					Copyright by <a href="/">{{ request()->getHost() }}</a>
				</p>
			</footer>
		@show

		@section('footer-assets')
			@include('layouts.includes.footer-assets')
		@show
	</body>
</html>
