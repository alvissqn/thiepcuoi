@extends('layouts.admin')
@section('header')
	@parent
@endsection

@section('content')
	<main class="row">
		<section class="col-lg-4 mt-2">
			@include('pages.admin.account.includes.navbar-menu', ['active' => 'notifications'])
		</section>
		<section class="col-lg-8 mt-2">
			<div class="card">
				<h4 class="card-header">
					{{ __('user/profile.account_notifications_heading_title') }}
				</h4>
				<hr>
				<section class="card-body accordion collapse-icon accordion-icon-rotate" id="pnotifications-list">
					<ul class="nav nav-tabs no-gutters">
						<li class="nav-item col-6">
							<a class="nav-link active font-small-3" data-toggle="tab" href="#notifications-unread">
								<i class="bx bx-bell align-top"></i>
								{{ __('users/notifications.header_notifications_heading_unread') }}
								(<span>{{ $getNotifications['unread'] }}</span>)
							</a>
						</li>
						<li class="nav-item col-6">
							<a class="nav-link font-small-3" data-toggle="tab" href="#notifications-readed">
								<i class="bx bx-list-check align-top"></i>
								{{ __('users/notifications.header_notifications_heading_readed') }}
							</a>
						</li>
					</ul>
					<div class="tab-content mt-2">

						<!-- Chưa đọc -->
						<div class="tab-pane active" id="notifications-unread">
							@foreach($getNotifications['items'] as $item)
								@if( $item->readed )
									@continue
								@endif
								@switch( $item->type )
									{{-- Tin nhắn --}}
									@case(0)
										@php
											$iconLabel = '<span class="badge-circle badge-circle-sm badge-circle-warning font-small-1"><i class="bx bx-envelope"></i></span>';
										@endphp
									@break;
									
									{{-- Thông báo --}}
									@case(1)
										@php
											$iconLabel = '<span class="badge-circle badge-circle-sm badge-circle-info font-small-1"><i class="bx bx-bell"></i></span>';
										@endphp
									@break;
								@endswitch
								@php
									$itemId = 'pnotification-item-'.$item->id;
								@endphp
								<div class="card collapse-header">
									<div class="card-header collapsed" data-toggle="collapse" data-target="#{{ $itemId }}" aria-expanded="false" onclick="headerNotification.maskAsReaded({{ $item->id }})">
										<span class="collapse-title font-small-3">
											<span class="align-middle">
												{!! $iconLabel !!}
												{{ $item->title }}
											</span>
										</span>
									</div>
									<div id="{{ $itemId }}" data-parent="#pnotifications-list" class="collapse">
										<div class="card-content">
											<div class="card-body">
												<div>
													{!! $item->content !!}
												</div>
												<div class="text-right font-small-2 text-gray">
													<i class="bx bx-time"></i>
													{{ date( Option::get('settings__general_time_format'), timestamp($item->created_at) ) }}
												</div>
											</div>
										</div>
									</div>
								</div>
							@endforeach
						</div>
						<!-- /Chưa đọc -->

						<!-- Đã đọc -->
						<div class="tab-pane" id="notifications-readed">
							@foreach($getNotifications['items'] as $item)
								@if( !$item->readed )
									@continue
								@endif
								@switch( $item->type )
									{{-- Tin nhắn --}}
									@case(0)
										@php
											$iconLabel = '<span class="badge-circle badge-circle-sm badge-circle-warning font-small-1"><i class="bx bx-envelope"></i></span>';
										@endphp
									@break;
									
									{{-- Thông báo --}}
									@case(1)
										@php
											$iconLabel = '<span class="badge-circle badge-circle-sm badge-circle-info font-small-1"><i class="bx bx-bell"></i></span>';
										@endphp
									@break;
								@endswitch
								@php
									$itemId = 'pnotification-item-'.$item->id;
								@endphp
								<div class="card collapse-header">
									<div class="card-header collapsed" data-toggle="collapse" data-target="#{{ $itemId }}" aria-expanded="false" onclick="headerNotification.maskAsReaded({{ $item->id }})">
										<span class="collapse-title font-small-3">
											<span class="align-middle">
												{!! $iconLabel !!}
												{{ $item->title }}
											</span>
										</span>
									</div>
									<div id="{{ $itemId }}" data-parent="#pnotifications-list" class="collapse">
										<div class="card-content">
											<div class="card-body">
												<div>
													{!! $item->content !!}
												</div>
												<div class="text-right font-small-2 text-gray">
													<i class="bx bx-time"></i>
													{{ date( Option::get('settings__general_time_format'), timestamp($item->created_at) ) }}
												</div>
											</div>
										</div>
									</div>
								</div>
							@endforeach
						</div>
						<!-- / Đã đọc -->

					</div>
				</section>
			</div>
		</section>
	</main>
@endsection

@section('footer')
	@parent
@endsection

@section('footer-assets')
	@parent
@endsection