@php
	use App\Services\UserServices;
	use App\Services\UserDataServices;
	use App\Helpers\Form;

	// Lấy dữ liệu
	$getItems = \App\Notification::where('type', config('notification.type.code.message') );

	// Lọc theo người tạo
	if( !empty( request()->user_id ) ){
		$getItems = $getItems->where('created_user_id', request()->user_id);
	}

	// Lọc theo này bắt đầu
	if( !empty( request()->date_start ) ){
		$dateStart = dateFormat(request()->date_start, 'd/m/Y', 'Y-m-d').' 00:00:00';
		$getItems = $getItems->where('created_at', '>', $dateStart);
	}

	// Lọc theo ngày kết thúc
	if( !empty( request()->date_end ) ){
		$dateEnd = dateFormat(request()->date_end, 'd/m/Y', 'Y-m-d').' 23:59:59';
		$getItems = $getItems->where('created_at', '<', $dateEnd);
	}

	// Tìm kiếm
	if( !empty( request()->keyword ) ){
		$getItems = $getItems->where(function($query) {
			foreach(['title', 'content'] as $findBy){
				$query->orWhere($findBy, 'LIKE', "%".request()->keyword."%");
			}
		});
	}

	$getItems = $getItems->orderBy('id', 'DESC')->paginate( paginationLimit() );

	$getRole = \App\Role::get()->keyBy('id');

	// Xóa các thông báo hết hạn
	\App\Services\NotificationServices::cleanup();
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
					<div class="row align-items-center mx-0 my-1 no-gutters">
						<div class="col-7">
							<h4 class="card-header">
								{{ __('users/notifications.heading_title') }}
							</h4>
						</div>
						<div class="col-4 text-right">
							<button type="button" class="btn btn-primary" onclick="formUpdateNotification(false)">
								{{ __('users/notifications.create_notification_btn_label') }}
							</button>
						</div>
						<div class="col-1 text-right">
							<i class="bx bx-sync link mr-2" onclick="runRefreshList()" style="font-size: 25px"></i>
						</div>
					</div>
					<div class="card-header-filter">
						<div class="row justify-content-end">
							<div class="col-md-3 pt-0 pl-1 pr-1 pb-1">
								{!!
									Form::datePicker([
										'placeholder' => __('general.date_start'),
										'name'        => 'date_start',
										'value'       => '',
										'position'    => 'bottom',
										'format'      => 'day/month/year',
										'config'      => [
											'allow'=>[
												'hours' => [],
												'minutes' => false,
												'days' => '1-31',
												'months' => '1-12',
												'weekDay' => [],
												'min' => ['y' => date('Y')-2, 'm' => 2, 'd' => 14],
												'max' => ['y' => date('Y'), 'm' => date('m'), 'd' => date('d')]
											],
											'value' => ['day' => date('d'), 'month' => date('m'), 'year' => date('Y') ]
										],
										'class'         => 'item-list-filter',
										'attr'          => '',
										'icon'          => 'bx-user',
										'icon_position' => 'left',
										'onchange'      => 'runRefreshList()'
									])
								!!}
							</div>
							<div class="col-md-3 pt-0 pl-1 pr-1 pb-1">
								{!!
									Form::datePicker([
										'placeholder' => __('general.date_end'),
										'name'        => 'date_end',
										'value'       => '',
										'position'    => 'bottom',
										'format'      => 'day/month/year',
										'config'      => [
											'allow'=>[
												'hours' => [],
												'minutes' => false,
												'days' => '1-31',
												'months' => '1-12',
												'weekDay' => [],
												'min' => ['y' => date('Y')-2, 'm' => 2, 'd' => 14],
												'max' => ['y' => date('Y'), 'm' => date('m'), 'd' => date('d')]
											],
											'value' => ['day' => date('d'), 'month' => date('m'), 'year' => date('Y') ]
										],
										'class'         => 'item-list-filter',
										'attr'          => '',
										'icon'          => 'bx-user',
										'icon_position' => 'left',
										'onchange'      => 'runRefreshList()'
									])
								!!}
							</div>
							<div class="col-md-3 pt-0 pl-1 pr-1 pb-1">
								{!!
									Form::select2([
										'title'         => __('users/notifications.filter_by_user_created'),
										'placeholder'   => '',
										'name'          => 'created_user_id',
										'class'         => 'select2-select-user-ajax item-list-filter',
										'attr'          => 'onchange="runRefreshList()"',
										'icon'          => '',
										'icon_position' => 'right',
										'options' => [
											'' => __('general.select_field_empty')
										],
										'selected' => [],
										'multiple' => false,
										'search'   => false
									])
								!!}
							</div>
							<div class="col-md-3 pt-0 pl-1 pr-1 pb-1">
								{!!
									Form::text([
										'title'         => '',
										'placeholder'   => __('users/notifications.search_by_content'),
										'name'          => 'keyword',
										'value'         => '',
										'class'         => 'item-list-filter',
										'attr'          => 'onkeyup="runRefreshList()"',
										'icon'          => 'bx-search',
										'icon_position' => 'left'
									])
								!!}
							</div>
						</div>
					</div><!-- /.card-header-filter -->
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
									<th style="width: 200px">
										{{ __('users/notifications.table_thead_created_by') }}
									</th>
									<th>
										{{ __('users/notifications.table_thead_title') }}
									</th>
									<th style="width: 250px">
										{{ __('users/notifications.table_thead_date') }}
									</th>
									<th style="width: 200px">
										{{ __('users/notifications.table_thead_receiver') }}
									</th>
									<th class="text-center" style="width: 150px">
										{{ __('users/notifications.table_thead_action') }}
									</th>
								</tr>
							</thead>
							<tbody>
								@php
									$i = ( ( request()->page ?? 1 ) - 1) * paginationLimit();
								@endphp
								@foreach($getItems as $item)
									@php
										$i++;
										$item->display_name    = UserServices::get($item->created_user_id, 'display_name');
										$item->created_at_time = $item->created_at->format( Option::get('settings__general_time_format') );
										if( $item->expired ){
											$item->expired_time    = date( Option::get('settings__general_time_format') , timestamp($item->expired) );
										}else{
											$item->expired_time = __('general.no');
										}
									@endphp
									<tr class="item-list-row" data-id="{{ $item->id }}">
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													#
												</div>
												<div style="width: 60%">
													{{ $i }}
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 20%">
													
												</div>
												<div style="width: 80%">
													<div class="row align-items-center">
														<div style="width: 65px">
															{!! UserServices::showAvatar($item->created_user_id, '45px') !!}
														</div>
														<div style="width: calc(100% - 65px)">
															{!! $item->display_name !!}
														</div>
													</div>
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('users/notifications.table_thead_title') }}
												</div>
												<div style="width: 60%">
													{!! $item->title !!}
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('users/notifications.table_thead_date') }}
												</div>
												<div style="width: 60%">
													<div>
														{{ __('users/notifications.table_item_created_at') }}:
														{!! dateText( $item->created_at->timestamp ) !!}
													</div>
													<div>
														{{ __('users/notifications.table_item_expired') }}:
														{{ $item->expired_time }}
													</div>
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('users/notifications.table_item_receiver') }}
												</div>
												<div style="width: 60%;">
													@php
														$item->receiver = '';
														$getReceiverRole = \App\RoleNotifications::select('roles.*')
															->where('notification_id', $item->id)
															->leftJoin('roles', 'roles.id', '=', 'role_notifications.role_id')
															->get();
													@endphp
													@foreach( $getReceiverRole as $role )
														@php
															$item->receiver .= '
																<div style="color: '.($role->color ?? '').'">
																	'.($role->name ?? '').'
																</div>
															';
														@endphp
													@endforeach
													@php
														$countReceiverUser = \App\UserNotifications::where('notification_id', $item->id)->count();
														if( $countReceiverUser > 0){
															$item->receiver .= '<div><b>+ '.$countReceiverUser .' users</b></div>';
														}
													@endphp
													{!! $item->receiver !!}
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('users/notifications.table_thead_action') }}
												</div>
												<div style="width: 60%" class="text-center">
													<button class="btn btn-sm btn-primary" type="button"  onclick="itemList.showDetail(this, '#modal-notification-detail', '{{ $item->title }}')">
														{{ __('users/notifications.btn_action_label') }}
													</button>
												</div>
											</div>
											@php
												$item->send_mail = $item->send_mail ? __('general.yes') : __('general.no');
											@endphp
											<textarea class="item-json-data hide">{!! json_encode($item, false) !!}</textarea>
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


	<!-- Gửi thông báo -->
	<section class="modal fade text-left" id="modal-update-notification">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"></h4>
					<button type="button" class="close" data-dismiss="modal">
						<i class="bx bx-x"></i>
					</button>
				</div>
				<section class="modal-body custom-scrollbar">
					<form action="{{ route('admin.users.notifications.send') }}" method="POST">
						<section class="row">
							<!-- Chọn người nhận -->
							<div class="col-md-6">
								<div class="card box-shadow-0">
									<h6 class="card-header bg-primary text-white">
										{{ __('users/notifications.create_notification_select_receiver') }}
									</h6>
									<hr>
									<div class="card-body border-left border-right border-bottom">
										<div class="mb-2">
											<div class="pb-1">
												{{ __('users/notifications.select_receiver_type_role') }}
											</div>
											{!!
												Form::checkbox([
													'name'    => 'roles',
													'class'   => '',
													'attr'    => '',
													'data' => ( call_user_func(function(){
														foreach(\App\Role::all() as $item){
															$out[$item->id] = $item->name;
														}
														return $out;
													}) ),
													'checked' => [],
													'disabled' => []
												])
											!!}
										</div>
										<div class="mb-2">
											{!!
												Form::select2([
													'title'         => __('users/notifications.select_receiver_type_user'),
													'placeholder'   => '',
													'name'          => 'users',
													'class'         => 'select2-select-user-ajax',
													'attr'          => '',
													'icon'          => '',
													'icon_position' => 'right',
													'options' => [
														'' => __('general.select_field_empty')
													],
													'selected' => [],
													'multiple' => true,
													'search'   => false
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
							<!-- /Chọn người nhận -->

							<!-- Nội dung thông báo -->
							<div class="col-md-6">
								<div class="card box-shadow-0">
									<h6 class="card-header bg-primary text-white">
										{{ __('users/notifications.create_notification_body_heading') }}
									</h6>
									<hr>
									<div class="card-body border-left border-right border-bottom">
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
							element: '#modal-update-notification form',
							success: function(){
								location.reload();
							}
						});
					</script>
				</section>
				<div class="modal-footer hide"></div>
			</div>
		</div>
	</section>
	<!-- / Gửi thông báo -->

	<!-- Chi tiết thông báo -->
	<section class="modal fade text-left" id="modal-notification-detail">
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
						<div class="row row-detail">
							<div style="width: 30%">
								{{ __('users/notifications.table_thead_created_by') }}:
							</div>
							<div style="width: 70%">
								${display_name}
							</div>
						</div>
						<div class="row row-detail">
							<div style="width: 30%">
								{{ __('users/notifications.table_thead_receiver') }}:
							</div>
							<div style="width: 70%">
								${receiver}
							</div>
						</div>
						<div class="row row-detail">
							<div style="width: 30%">
								{{ __('users/notifications.table_item_created_at') }}:
							</div>
							<div style="width: 70%">
								${created_at_time}
							</div>
						</div>
						<div class="row row-detail">
							<div style="width: 30%">
								{{ __('users/notifications.table_item_expired') }}:
							</div>
							<div style="width: 70%">
								${expired_time}
							</div>
						</div>
						<div class="row row-detail">
							<div style="width: 30%">
								{{ __('users/notifications.table_thead_send_mail') }}:
							</div>
							<div style="width: 70%">
								${send_mail}
							</div>
						</div>
						<div class="row row-detail">
							<div style="width: 30%">
								{{ __('users/notifications.table_thead_title') }}:
							</div>
							<div style="width: 70%">
								${title}
							</div>
						</div>
						<div class="row row-detail">
							<div style="width: 30%">
								{{ __('users/notifications.table_thead_content') }}:
							</div>
							<div style="width: 70%">
								${content}
							</div>
						</div>
						<div class="text-center p-2">
							<button type="button" class="btn btn-danger" onclick="itemList.delete({ url: '{{ route('admin.users.notifications.delete') }}', id: ${id}, success: function(){ location.reload(); } })">
								<i class="bx bx-trash"></i>
							</button>
						</div>
					</template>
					<section></section>
				</div>
				<div class="modal-footer hide"></div>
			</div>
		</div>
	</section>
	<!-- / Chi tiết thông báo -->


@endsection

@section('footer')
	@parent
@endsection

@section('footer-assets')
	@parent
	<script src="/assets/plugins/forms/select2/select-user-ajax/scripts.js"></script>
	<script type="text/javascript">
		/*
		 * Load lại danh sách
		 */
		function runRefreshList(){
			itemList.reload({
				element: '#item-list',
				formFilter: '.item-list-filter',
				data: {page: 1},
				success: function(){

				}
			});
		}
		/*
		 * Phân trang ajax
		 */
		itemList.pagination({
			element: '#item-list',
			formFilter: '.item-list-filter',
			data: {}
		});

		/*
		 * Tự động load
		 */
		itemList.autoReload({
			element: '#item-list',
			formFilter: '.item-list-filter',
			data: {},
			timer: 20,
			success: function(){

			}
		});

		/*
		 * Click tạo hoặc sửa thông báo
		 */
		function formUpdateNotification(self){
			$('#modal-update-notification').modal('show');
		}
	</script>
@endsection