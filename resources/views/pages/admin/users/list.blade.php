@php	
	use App\Services\UserServices;
	use App\Services\UserDataServices;
	use App\Helpers\Form;

	// Lấy dữ liệu
	$getItems = \App\User::with('role')->where('id', '!=', Auth::id() );

	// Tìm kiếm
	if( !empty( request()->keyword ) ){
		// Tìm theo id
		if( strpos( request()->keyword, '"' ) !== false ){
			$getItems = $getItems->where('id', toNumber( request()->keyword ) );
		}else{
			$getItems = $getItems->where(function($query) {
				foreach(['name', 'email', 'phone_number'] as $findBy){
					$query->orWhere($findBy, 'LIKE', "%".request()->keyword."%");
				}
			});
		}
	}

	// Lọc theo trạng thái
	$status = request()->status ?? config('user.status.code.actived');
	$getItems = $getItems->where('status', $status);

	// Lọc theo chức vụ
	if( strlen( request()->role_id ) > 0 ){
		$getItems = $getItems->where('role_id', request()->role_id);
	}

	// Ẩn các tài khoản admin với các tài khoản không có quyền
	if( !Permission::has('admin') ){
		$getItems = $getItems->where('role_id', '!=', config('user.roles.code.admin') );
	}

	$getItems = $getItems->orderBy('id', 'DESC')->paginate( paginationLimit() );

	$getRole = \App\Role::get()->keyBy('id');
@endphp
@extends('layouts.admin')
@section('header')
	@parent
	<script src="/assets/plugins/item-list/scripts.js"></script>
	<script src="/assets/plugins/form-ajax-save/scripts.js"></script>
@endsection

@section('content')
	<main class="row mt-2">
		<section class="col-12">
			<div class="card">
				<div class="align-items-center">
					<div class="row align-items-center mr-0 ml-0">
						<div class="col-12 col-md-8">
							<h4 class="card-header">
								{{ __('users/list.heading_title') }}
							</h4>
						</div>
						<div class="col-12 col-md-4 text-right">
							<button class="btn btn-primary" onclick="userList.addUser.showModal()">
								Thêm tài khoản
							</button>
							<i class="bx bx-sync link mr-2" onclick="runRefreshList()" style="font-size: 25px"></i>
						</div>
					</div>
					<div class="card-header-filter">
						<div class="row justify-content-end">
							<div class="col-md-4 pt-0 pl-1 pr-1 pb-1">
								{!!
									Form::text([
										'title'         => '',
										'placeholder'   => 'Tìm tên, email, SĐT, "id"',
										'name'          => 'keyword',
										'value'         => '',
										'class'         => 'item-list-filter',
										'attr'          => 'onkeyup="runRefreshList()"',
										'icon'          => 'bx-search',
										'icon_position' => 'left'
									])
								!!}
							</div>
							<div class="col-md-2 pt-0 pl-1 pr-1 pb-1"></div>
							<div class="col-md-3 pt-0 pl-1 pr-1 pb-1">
								{!!
									Form::select2([
										'title'         => 'Trạng thái',
										'placeholder'   => '',
										'name'          => 'status',
										'class'         => 'item-list-filter',
										'attr'          => 'onchange="runRefreshList()"',
										'icon'          => '',
										'icon_position' => 'right',
										'options'       => ( call_user_func(function(){
											$out = ['' => __('general.select_field_empty')];
											foreach(config('user.status.text') as $id => $label){
												$out[$id] = $label;
											}
											return $out;
										}) ),
										'selected'      => [ config('user.status.code.actived') ],
										'multiple'      => false,
										'search'        => false
									])
								!!}
							</div>
							<div class="col-md-3 pt-0 pl-1 pr-1 pb-1">
								{!!
									Form::select2([
										'title'         => __('users/list.filter_by_role'),
										'placeholder'   => '',
										'name'          => 'role_id',
										'class'         => 'item-list-filter',
										'attr'          => 'onchange="runRefreshList()"',
										'icon'          => '',
										'icon_position' => 'right',
										'options' => ( call_user_func(function(){
											$out = ['' => __('general.select_field_empty')];
											foreach(\App\Role::all() as $item){
												$out[$item->id] = $item->name;
											}
											return $out;
										}) ),
										'selected' => [],
										'multiple' => false,
										'search'   => false
									])
								!!}
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="card-body card-body-table" id="item-list">
					<div class="table-responsive table-responsive-v">
						<table class="table table-hover">
							<thead>
								<tr>
									<th style="width: 60px">
										ID
									</th>
									<th>
										{{ __('users/list.table_thead_user_name') }}
									</th>
									<th style="width: 200px">
										{{ __('users/list.table_thead_phone_number') }}
									</th>
									<th style="width: 200px">
										{{ __('users/list.table_thead_registered_at') }}
									</th>
									<th style="width: 200px">
										{{ __('users/list.table_thead_role') }}
									</th>
									<th class="text-center" style="width: 150px">
										{{ __('users/list.table_thead_action') }}
									</th>
								</tr>
							</thead>
							<tbody>
								@php
									$i = 0;
								@endphp
								@foreach($getItems as $item)
									@php
										$i++;
										$item->display_name = UserServices::get($item->id, 'display_name');
										$item->time = $item->created_at->format( Option::get('settings__general_time_format') );
									@endphp
									<tr class="item-list-row" data-id="{{ $item->id }}">
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													ID
												</div>
												<div style="width: 60%">
													{{ $item->id }}
												</div>
											</div>
										</td>
										<td>
											@php
												$item->avatar        = UserServices::avatar($item->email);
												$item->active_status = UserServices::activeStatus($item->id);
											@endphp
											<div class="table-sm-row">
												<div style="width: 20%">
													
												</div>
												<div style="width: 80%">
													<div class="row align-items-center">
														<div style="width: 65px">
															{!! UserServices::showAvatar($item->id, '45px') !!}
														</div>
														<div style="width: calc(100% - 65px)">
															<div>
																{!! $item->display_name !!}
																<i class="bx bx-{{ config("user.gender.text.{$item->gender}") }}"></i>
															</div>
															<div>
																{!! $item->email !!}
															</div>
														</div>
													</div>
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('users/list.table_thead_phone_number') }}
												</div>
												<div style="width: 60%">
													{!! $item->phone_number !!}
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('users/list.table_thead_registered_at') }}
												</div>
												<div style="width: 60%">
													{!! dateText( $item->created_at->timestamp ) !!}
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('users/list.table_thead_role') }}
												</div>
												<div style="width: 60%; color: {!! $item->role->color !!}">
													{!! $item->role->name !!}
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('users/list.table_thead_action') }}
												</div>
												<div style="width: 60%" class="text-center">
													<div class="btn-group">
														<div class="dropdown">
															<button class="btn btn-sm btn-primary dropdown-toggle" type="button"data-toggle="dropdown">
																{{ __('users/list.btn_action_label') }}
															</button>
															<div class="dropdown-menu">
																<span class="dropdown-item link" onclick="itemList.showDetail(this, '#modal-item-list-detail', '{{ $item->name }}')">
																	<i class="bx bx-info-circle"></i>
																	{{ __('users/list.btn_action_info') }}
																</span>
																<span class="dropdown-item"  onclick="itemList.showDetail(this, '#modal-user-change-password', '{{ $item->name }}')">
																	<i class="bx bx-lock"></i>
																	{{ __('users/list.btn_action_change_password') }}
																</span>
																<span class="dropdown-item"  onclick="itemList.formInsertData('#modal-user-send-notification', this, '{{ $item->name }}'); showUserNotifications(this)">
																	<i class="bx bx-bell"></i>
																	{{ __('users/list.btn_action_send_notification') }}
																</span>
															</div>
														</div>
													</div>
												</div>
											</div>
											@php
												// Tên quyền
												$item->role_name = '
													<span style="color: '.($getRole[ $item->role_id ]->color ?? null).'">
														'.($getRole[ $item->role_id ]->name ?? null).'
													</span>
												';

												// Thời gian đăng ký, online
												$item->last_online = dateText( UserDataServices::get('last_online', $item->id) ?? 0 );
												$item->registered_at = $item->created_at->format( Option::get('settings__general_time_format') );

												// Đăng nhập qua
												if( UserDataServices::get('social_google_id', $item->id) ){
													$item->register_via = __('user/general.register_via_google');
												}else if( UserDataServices::get('social_facebook_id', $item->id) ){
													$item->register_via = __('user/general.register_via_facebook');
												}else{
													$item->register_via = __('user/general.register_handmade');
												}

												// Link đăng nhập nhanh
												$item->login_with_private_key = url('/user/login-with-private-key/'.UserServices::createAuthPrivateKey($item->id, $item->password) );

												// Id người nhận thông báo
												$item->users = $item->id;

												// Danh sách thông báo
												$item->notifications = '
													<section class="accordion collapse-icon accordion-icon-rotate" id="notifications-list">
												';
												$getUserNotifications = \App\Services\NotificationServices::getNotificationsByUserId([
													'userId'       => $item->id,
													'limit'        => 10,
													'replace_name' => false
												] );
												foreach( $getUserNotifications['items'] as $nt){
													$item->notifications .= '
														<div class="card collapse-header">
															<div class="card-header collapsed" data-toggle="collapse" data-target="#notification-item-'.$nt->id.'">
																<span class="collapse-title">
																	<span class="align-middle">
																		'.( $nt->readed ?
																			'
																				<span class="badge badge-info">
																					Readed
																				</span>
																			' : '
																				<span class="badge badge-light">
																					Unread
																				</span>
																		' ).'
																		'.$nt->title.'
																	</span>
																</span>
															</div>
															<div id="notification-item-'.$nt->id.'" data-parent="#notifications-list" class="collapse">
																<div class="card-content">
																	<div class="card-body">
																		<div class="row row-detail no-border">
																			<div style="width: 30%">
																				'.__('users/notifications.table_thead_created_by').':
																			</div>
																			<div style="width: 70%">
																				'.UserServices::get($nt->created_user_id, 'display_name').'
																			</div>
																		</div>
																		<div class="row row-detail no-border">
																			<div style="width: 30%">
																				'.__('users/notifications.table_item_created_at').':
																			</div>
																			<div style="width: 70%">
																				'.date( \Option::get('settings__general_time_format'), timestamp($nt->created_at) ).'
																			</div>
																		</div>
																		<div class="row row-detail no-border">
																			<div style="width: 30%">
																				'.__('users/notifications.table_item_expired').':
																			</div>
																			<div style="width: 70%">
																				'.(empty($nt->expired) ? 
																					__('general.no')
																				: 
																					date( Option::get('settings__general_time_format') , timestamp($nt->expired) )
																				).'
																			</div>
																		</div>
																		<div class="row row-detail no-border">
																			<div style="width: 30%">
																				'.__('users/notifications.table_thead_send_mail').':
																			</div>
																			<div style="width: 70%">
																				'.( $nt->send_mail ? __('general.yes') : __('general.no') ).'
																			</div>
																		</div>
																		<div class="row row-detail no-border">
																			<div style="width: 30%">
																				'.__('users/notifications.table_item_read_status').':
																			</div>
																			<div style="width: 70%">
																				'.( $nt->readed ?
																					'
																						<span class="badge badge-info">
																							'.__('general.readed').'
																						</span>
																					' : '
																						<span class="badge badge-light">
																							'.__('general.unread').'
																						</span>
																				' ).'
																			</div>
																		</div>
																		<div class="pt-1">
																			'.str_replace('@name', $item->name, $nt->content).'
																		</div>
																	</div>
																</div>
															</div>
														</div>
													';
												}
												$item->notifications .= '</section>';
											@endphp
											<textarea class="item-json-data hide">@json($item)</textarea>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					@if( $getItems->count() == 0 )
						<div class="text-center p-2">
							{{ __('general.item_list_is_empty') }}
						</div>
					@endif
					<div class="pagination-center pt-1">
						{!! $getItems->links() !!}
					</div>
					<div class="row align-items-center px-md-2 py-1">
						<div style="width: calc(100% - 150px)">
							{{ __('general.total_record') }}: <b>{{ $getItems->total() }}</b>
						</div>
						<div style="width: 150px">
							<div class="">
								{!!
									Form::select2([
										'title'         => __('general.pagination_limit'),
										'placeholder'   => '',
										'name'          => 'pagination_limit',
										'class'         => 'item-list-filter',
										'attr'          => 'onchange="runRefreshList()"',
										'icon'          => '',
										'icon_position' => 'right',
										'options'       => array_replace( [Option::get('settings__general')['pagination_limit'] ?? 5 => Option::get('settings__general')['pagination_limit'] ?? 5], config('general.pagination_limit') ),
										'selected'      => [paginationLimit()],
										'multiple'      => false,
										'search'        => false
									])
								!!}
							</div>
						</div>
					</div>
				</div>
			</div><!--/.card-->
		</section>
	</main>

	<!--Thêm tài khoản mới -->
	<section class="modal fade text-left" id="modal-add-user">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						Thêm tài khoản
					</h4>
					<button type="button" class="close" data-dismiss="modal">
						<i class="bx bx-x"></i>
					</button>
				</div>
				<div class="modal-body custom-scrollbar">
						<form class="tab-pane" action="{{ route('admin.users.add-user') }}" method="POST" id="form-add-user">
							<div class="mb-2 mt-2">
								{!!
									Form::text([
										'title'         => '',
										'placeholder'   => __('user/general.profile_name'),
										'name'          => 'name',
										'value'         => '',
										'class'         => '',
										'attr'          => '',
										'icon'          => 'bx-user',
										'icon_position' => 'left'
									])
								!!}
							</div>
							<div class="mb-2 mt-2">
								{!!
									Form::text([
										'title'         => '',
										'placeholder'   => 'Mật khẩu',
										'name'          => 'password',
										'value'         => '',
										'class'         => '',
										'attr'          => '',
										'icon'          => 'bx-lock',
										'icon_position' => 'left'
									])
								!!}
							</div>
							<div class="mb-2 mt-2">
								{!!
									Form::text([
										'title'         => '',
										'placeholder'   => __('user/general.profile_phone_number'),
										'name'          => 'phone_number',
										'value'         => '',
										'class'         => '',
										'attr'          => '',
										'icon'          => 'bx-phone',
										'icon_position' => 'left'
									])
								!!}
							</div>
							<div class="mb-2">
								{!!
									Form::text([
										'title'         => '',
										'placeholder'   => __('user/general.profile_email'),
										'name'          => 'email',
										'value'         => '',
										'class'         => '',
										'attr'          => '',
										'icon'          => 'bx-envelope',
										'icon_position' => 'left'
									])
								!!}
							</div>
							@if( Permission::has('admin') )
								<div class="mb-2">
									{!!
										Form::select2([
											'title'   => __('user/general.role_name'),
											'name'          => 'role_id',
											'class'         => '',
											'attr'          => 'data-value="2"',
											'icon'          => '',
											'icon_position' => 'right',
											'options' => ( call_user_func(function(){
												foreach(\App\Role::all() as $item){
													$out[$item->id] = $item->name;
												}
												return $out;
											}) ),
											'selected' => [2],
											'multiple' => false,
											'search'   => false
										])
									!!}
								</div>
							@endif
							<div class="form-notify alert alert-danger hide"></div>
							<div class="text-center mb-2">
								<button type="button" class="form-save btn btn-primary" onclick="userList.addUser.submit(this)">
									{{ __('user/general.update_profile_btn_label') }}
								</button>
							</div>
						</form>
					<section></section>
				</div>
				<div class="modal-footer hide"></div>
			</div>
		</div>
	</section>
	<!-- / Thêm tài khoản mới -->

	<!-- Thông tin tài khoản -->
	<section class="modal fade text-left" id="modal-item-list-detail">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"></h4>
					<button type="button" class="close" data-dismiss="modal">
						<i class="bx bx-x"></i>
					</button>
				</div>
				<div class="modal-body custom-scrollbar">
					<template>
						<ul class="nav nav-tabs">
							<li class="nav-item col-6">
								<a class="nav-link active" data-toggle="tab" href="#tab-user-info">
									<i class="bx bx-info-circle align-top"></i>
									{{ __('user/general.tab_label_info') }}
								</a>
							</li>
							<li class="nav-item col-6">
								<a class="nav-link" data-toggle="tab" href="#tab-user-edit-profile" >
									<i class="bx bx-edit align-top"></i>
									{{ __('user/general.tab_label_edit_profile') }}
								</a>
							</li>
						</ul>
						<div class="tab-content pt-1">

							<!-- Thông tin cá nhân -->
							<div class="tab-pane active" id="tab-user-info">
								<div class="text-center">
									<div class="avatar">
										<img src="${avatar}" style="width: 80px; height: 80px">
										<span class="avatar-status-${active_status}"></span>
									</div>
								</div>
								<div class="row row-detail">
									<div style="width: 30%">
										{{ __('user/general.profile_last_online') }}:
									</div>
									<div style="width: 70%">
										${last_online}
									</div>
								</div>
								<div class="row row-detail">
									<div style="width: 30%">
										{{ __('user/general.profile_register_at') }}:
									</div>
									<div style="width: 70%">
										${registered_at}
									</div>
								</div>
								<div class="row row-detail">
									<div style="width: 30%">
										{{ __('user/general.profile_phone_number') }}:
									</div>
									<div style="width: 70%">
										${phone_number}
									</div>
								</div>
								<div class="row row-detail">
									<div style="width: 30%">
										{{ __('user/general.profile_email') }}:
									</div>
									<div style="width: 70%">
										${email}
									</div>
								</div>
								<div class="row row-detail">
									<div style="width: 30%">
										{{ __('user/general.role_name') }}:
									</div>
									<div style="width: 70%">
										${role_name}
									</div>
								</div>
								<div class="row row-detail">
									<div style="width: 30%">
										{{ __('user/general.register_via') }}:
									</div>
									<div style="width: 70%">
										${register_via}
									</div>
								</div>
								<div class="row row-detail">
									<div style="width: 30%">
										{{ __('user/general.login_with_private_key') }}:
									</div>
									<div style="width: 70%">
										<div class="row no-gutters align-items-center">
											<div class="col-10">
												<input type="text" class="form-control" value="${login_with_private_key}">
											</div>
											<div class="text-center p-1 col-2">
												<a class="btn btn-warning btn-sm" href="${login_with_private_key}">
													<i class="bx bx-log-in"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /Thông tin cá nhân -->

							<!-- Sửa thông tin -->
							<form class="tab-pane" id="tab-user-edit-profile" action="{{ route('admin.users.update-profile') }}" method="POST">
								<input type="hidden" name="id" value="${id}">
								<div class="mb-2 mt-2">
									{!!
										Form::text([
											'title'         => '',
											'placeholder'   => __('user/general.profile_name'),
											'name'          => 'name',
											'value'         => '${name}',
											'class'         => '',
											'attr'          => '',
											'icon'          => 'bx-user',
											'icon_position' => 'left'
										])
									!!}
								</div>
								<div class="mb-2 mt-2">
									{!!
										Form::text([
											'title'         => '',
											'placeholder'   => __('user/general.profile_phone_number'),
											'name'          => 'phone_number',
											'value'         => '${phone_number}',
											'class'         => '',
											'attr'          => '',
											'icon'          => 'bx-phone',
											'icon_position' => 'left'
										])
									!!}
								</div>
								<div class="mb-2">
									{!!
										Form::text([
											'title'         => '',
											'placeholder'   => __('user/general.profile_email'),
											'name'          => 'email',
											'value'         => '${email}',
											'class'         => '',
											'attr'          => '',
											'icon'          => 'bx-envelope',
											'icon_position' => 'left'
										])
									!!}
								</div>
								@if( Permission::has('admin') )
									<div class="mb-2">
										{!!
											Form::select2([
												'title'   => __('user/general.role_name'),
												'name'          => 'role_id',
												'class'         => '',
												'attr'          => 'data-selected="${role_id}"',
												'icon'          => '',
												'icon_position' => 'right',
												'options' => ( call_user_func(function(){
													foreach(\App\Role::all() as $item){
														$out[$item->id] = $item->name;
													}
													return $out;
												}) ),
												'selected' => [],
												'multiple' => false,
												'search'   => false
											])
										!!}
									</div>
								@endif
								<div class="mb-2">
									{!!
										Form::select2([
											'title'   => 'Trạng thái',
											'name'          => 'status',
											'class'         => '',
											'attr'          => 'data-selected="${status}"',
											'icon'          => '',
											'icon_position' => 'right',
											'options' => ( call_user_func(function(){
												foreach( config('user.status.text') as $id => $label){
													$out[$id] = $label;
												}
												return $out;
											}) ),
											'selected' => [],
											'multiple' => false,
											'search'   => false
										])
									!!}
								</div>
								<div class="form-notify alert alert-danger hide"></div>
								<div class="text-center mb-2">
									<button type="button" class="form-save btn btn-primary" onclick="formAjaxSaveCustom('#tab-user-edit-profile')">
										{{ __('user/general.update_profile_btn_label') }}
									</button>
								</div>
							</form>
							<!-- / Sửa thông tin -->
						</div>
					</template>
					<section></section>
				</div>
				<div class="modal-footer hide"></div>
			</div>
		</div>
	</section>
	<!-- / Thông tin tài khoản -->

	<!-- Đổi mật khẩu -->
	<section class="modal fade text-left" id="modal-user-change-password">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"></h4>
					<button type="button" class="close" data-dismiss="modal">
						<i class="bx bx-x"></i>
					</button>
				</div>
				<div class="modal-body custom-scrollbar">
					<template>
						<form class="tab-pane" id="tab-user-change-password" action="{{ route('admin.users.update-password') }}" method="POST">
							<input type="hidden" name="id" value="${id}">
							<div class="mb-2">
								{!!
									Form::text([
										'title'         => '',
										'placeholder'   => __('user/general.profile_new_password'),
										'name'          => 'password',
										'value'         => '',
										'class'         => '',
										'attr'          => '',
										'icon'          => 'bx-lock',
										'icon_position' => 'left'
									])
								!!}
							</div>
							<div class="form-notify alert alert-danger hide"></div>
							<div class="text-center mb-2">
								<button type="button" class="form-save btn btn-primary" onclick="formAjaxSaveCustom('#tab-user-change-password')">
									{{ __('user/general.update_profile_btn_label') }}
								</button>
							</div>
						</form>
					</template>
					<section></section>
				</div>
				<div class="modal-footer hide"></div>
			</div>
		</div>
	</section>
	<!-- / Đổi mật khẩu -->

	<!-- Gửi thông báo -->
	<section class="modal fade text-left" id="modal-user-send-notification">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"></h4>
					<button type="button" class="close" data-dismiss="modal">
						<i class="bx bx-x"></i>
					</button>
				</div>
				<section class="modal-body custom-scrollbar">
					<ul class="nav nav-tabs">
						<li class="nav-item col-6">
							<a class="nav-link active" data-toggle="tab" href="#tab-user-send-notification">
								<i class="bx bx-send align-top"></i>
								{{ __('user/general.tab_label_send_notification') }}
							</a>
						</li>
						<li class="nav-item col-6">
							<a class="nav-link" data-toggle="tab" href="#tab-user-list-notification" >
								<i class="bx bx-mail-send align-top"></i>
								{{ __('user/general.tab_label_list_notifications') }}
							</a>
						</li>
					</ul>
					<div class="tab-content pt-1">

						<!-- Gửi thông báo -->
						<div class="tab-pane active" id="tab-user-send-notification">
							<form action="{{ route('admin.users.notifications.send') }}" method="POST">
								<section>
									<!-- Nội dung thông báo -->
									<div>
										<div class="card box-shadow-0">
											<div class="card-body p-0">
												<input type="hidden" name="users[]">
												<div class="mb-2">
													{!!
														Form::text([
															'title'         => '',
															'placeholder'   => __('users/notifications.create_notification_body_title'),
															'name'          => 'title',
															'value'         => '',
															'class'         => '',
															'attr'          => '',
															'icon'          => '',
															'icon_position' => 'left'
														])
													!!}
												</div>
												<div class="mb-2">
													{!!
														Form::editor([
															'title'       => __('users/notifications.create_notification_body_content'),
															'placeholder' => '',
															'name'        => 'content',
															'value'       => '<p>Hi: <b>@name</b> , Welcome to website</p>',
															'rows'        => 4,
															'class'       => '',
															'attr'        => '',
														])
													!!}
												</div>
												<div class="mb-2">
													{!!
														Form::switch([
															'label'   => __('users/notifications.select_receiver_email'),
															'name'    => 'send_mail',
															'value'   => 1,
															'checked' => false,
															'class'   => '',
															'attr'    => ''
														])
													!!}
												</div>
												<div class="mb-2">
													{!!
														Form::datePicker([
															'placeholder' => __('users/notifications.select_receiver_expired'),
															'name'        => 'expired',
															'value'       => '',
															'position'    => 'top',
															'format'      => 'hour day/month/year',
															'config'      => [
																'allow'=>[
																	'hours' => [ [0,23] ],
																	'minutes' => true,
																	'days' => '1-31',
																	'months' => '1-12',
																	'weekDay' => [],
																	'min' => ['y' => date('Y'), 'm' => date('m'), 'd' => date('d')],
																	'max' => ['y' => date('Y') + 5, 'm' => 12, 'd' => 31]
																],
																'value' => ['day' => date('d'), 'month' => date('m'), 'year' => date('Y') ]
															],
															'class'         => '',
															'attr'          => '',
															'icon'          => '',
															'icon_position' => 'left',
															'onchange'      => ''
														])
													!!}
												</div>
											</div><!-- /.card-body -->
										</div>
									</div>
									<!-- / Nội dung thông báo -->
								</section>
								<div class="form-notify alert alert-danger hide"></div>
								<div class="text-center mb-2">
									<button type="button" class="form-save btn btn-primary">
										{{ __('user/general.update_profile_btn_label') }}
									</button>
								</div>
							</form>
							<script type="text/javascript">
								formAjaxSave.init({
									element: '#tab-user-send-notification>form',
									success: function(){
										location.reload();
									}
								});
							</script>
						</div>
						<!-- /Thông tin cá nhân -->

						<!-- Danh sách thông báo -->
						<div class="tab-pane" id="tab-user-list-notification">
							
						</div>
						<!-- / Danh sách thông báo -->
					</div>
				</section>
				<div class="modal-footer hide"></div>
			</div>
		</div>
	</section>
	<!-- / Gửi thông báo -->

@endsection

@section('footer')
	@parent
@endsection

@section('footer-assets')
	@parent
	<script type="text/javascript">
		function runRefreshList(){
			itemList.reload({
				element: '#item-list',
				formFilter: '.item-list-filter',
				data: {page: 1},
				success: function(){

				}
			});
		}

		itemList.pagination({
			element: '#item-list',
			formFilter: '.item-list-filter',
			data: {}
		});
		itemList.autoReload({
			element: '#item-list',
			formFilter: '.item-list-filter',
			data: {},
			timer: 20,
			success: function(){

			}
		});

		/*
		 * Ấn nút cập nhật thông tin tài khoản
		 */
		function formAjaxSaveCustom(element){
			formAjaxSave.send({
				element: element,
				success: function(){
					$('.modal').modal('hide');
					runRefreshList();
					toastr.success('{{ __('general.update_success') }}');
				}
			});
		}

		/*
		 * Click xem thông báo của tài khoản
		 */
		function showUserNotifications(self){
			var dataEl = $(self).parents('.item-list-row').find('.item-json-data');
			var data = JSON.parse( dataEl.val() ).notifications;
			$('#tab-user-list-notification').html(data);
		}

		const userList = {
			addUser: {
				/*
				 * Hiện form thêm tài khoản
				 */
				showModal: () => {
					itemList.formInsertData('#modal-add-user', this, 'Thêm tài khoản');
				},

				/*
				 * Ấn nút thêm tài khoản
				 */
				submit: (self) => {
					formAjaxSave.send({
						element: '#form-add-user',
						success: function(){
							$('.modal').modal('hide');
							runRefreshList();
							toastr.success('Thêm tài khoản thành công');
						}
					});
				}
			}
		};
	</script>
@endsection