@php	
	use App\Services\UserServices;
	use App\Services\UserDataServices;
	use App\Helpers\Form;

	// Lấy dữ liệu
	$getLogs = \App\Log::query();

	// Lọc theo người dùng
	if( !empty( request()->user_id ) ){
		$getLogs = $getLogs->where('user_id', request()->user_id);
	}

	// Lọc theo chuyên mục
	if( !empty( request()->action ) ){
		$getLogs = $getLogs->where('action', request()->action);
	}

	// Lọc theo này bắt đầu
	if( !empty( request()->date_start ) ){
		$dateStart = dateFormat(request()->date_start, 'd/m/Y', 'Y-m-d').' 00:00:00';
		$getLogs = $getLogs->where('created_at', '>', $dateStart);
	}

	// Lọc theo ngày kết thúc
	if( !empty( request()->date_end ) ){
		$dateEnd = dateFormat(request()->date_end, 'd/m/Y', 'Y-m-d').' 23:59:59';
		$getLogs = $getLogs->where('created_at', '<', $dateEnd);
	}
	$getLogs = $getLogs->orderBy('id', 'DESC')->paginate(paginationLimit());

@endphp
@extends('layouts.admin')
@section('header')
	@parent
	<script src="/assets/plugins/item-list/scripts.js"></script>
@endsection

@section('content')

	<main class="row mt-2">
		<section class="col-12">
			<div class="card">
				<div class="align-items-center">
					<div class="row align-items-center mr-0 ml-0">
						<div class="col-10">
							<h4 class="card-header">
								{{ __('admin/tools/logs.heading_title') }}
							</h4>
						</div>
						<div class="col-2 text-right">
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
										'title'         => __('admin/tools/logs.find_by_user'),
										'placeholder'   => '',
										'name'          => 'user_id',
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
									Form::select2([
										'title'         => __('admin/tools/logs.table_thead_category'),
										'placeholder'   => '',
										'name'          => 'action',
										'class'         => 'item-list-filter',
										'attr'          => 'onchange="runRefreshList()"',
										'icon'          => '',
										'icon_position' => 'right',
										'options' => ( call_user_func(function(){
											$out = ['' => __('general.select_field_empty')];
											$category = [];
											foreach(\App\LogCategory::all() as $item){
												$category[$item->name][$item->action] = __('admin/tools/logs_action.'.$item->action);
											}
											foreach($category as $label => $item){
												$out[] = [
													'label'   => __('admin/tools/logs_categories.'.$label),
													'options' => $item
												];
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
										#
									</th>
									<th>
										{{ __('admin/tools/logs.table_thead_user_name') }}
									</th>
									<th>
										{{ __('admin/tools/logs.table_thead_category') }}
									</th>
									<th>
										{{ __('admin/tools/logs.table_thead_action') }}
									</th>
									<th>
										{{ __('admin/tools/logs.table_thead_time') }}
									</th>
									<th class="text-center">
										{{ __('admin/tools/logs.table_thead_detail') }}
									</th>
								</tr>
							</thead>
							<tbody>
								@php
									$i = ( ( request()->page ?? 1 ) - 1) * paginationLimit();
								@endphp
								@foreach($getLogs as $item)
									@php
										$i++;
										$item->old_value = unserialize($item->old_value);
										$item->new_value = unserialize($item->new_value);
										$item->display_name = UserServices::get($item->user_id, 'display_name');
										$item->time = $item->created_at->format( Option::get('settings__general_time_format') );
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
												<div style="width: 40%">
													{{ __('admin/tools/logs.table_thead_user_name') }}
												</div>
												<div style="width: 60%">
													{!! $item->display_name !!}
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('admin/tools/logs.table_thead_category') }}
												</div>
												<div style="width: 60%">
													{{ __('admin/tools/logs_categories.'.$item->category) }}
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('admin/tools/logs.table_thead_action') }}
												</div>
												<div style="width: 60%">
													{{ __('admin/tools/logs_action.'.$item->action) }}
													@if( $item->action_value )
														: <b style="margin-left: 5px">{{ $item->action_value }}</b>
													@endif
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('admin/tools/logs.table_thead_time') }}
												</div>
												<div style="width: 60%">
													{{ dateText($item->created_at->timestamp) }}
												</div>
											</div>
										</td>
										<td>
											<div class="table-sm-row">
												<div style="width: 40%">
													{{ __('admin/tools/logs.table_thead_detail') }}
												</div>
												<div style="width: 60%" class="text-center">
													<button class="btn btn-sm btn-primary" onclick="actionLogs.showDetail(this)">
														{{ __('admin/tools/logs.table_thead_detail') }}
													</button>
													<textarea class="item-json-data hide">@json($item)</textarea>
												</div>
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					@if( $getLogs->count() == 0 )
						<div class="text-center p-2">
							{{ __('general.item_list_is_empty') }}
						</div>
					@endif
					<div class="pagination-center pt-1">
						{!! $getLogs->links() !!}
					</div>
					<div class="row align-items-center px-md-2 py-1">
						<div style="width: calc(100% - 150px)">
							{{ __('general.total_record') }}: <b>{{ $getLogs->total() }}</b>
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
	<section class="modal fade text-left" id="modal-logs-detail">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"></h4>
					<button type="button" class="close" data-dismiss="modal">
						<i class="bx bx-x"></i>
					</button>
				</div>
				<div class="modal-body custom-scrollbar">
					
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</section><!-- /.modal -->
@endsection

@section('footer')
	@parent
@endsection

@section('footer-assets')
	@parent
	<script src="/assets/plugins/forms/select2/select-user-ajax/scripts.js"></script>
	<script src="/assets/plugins/action-logs/scripts.js"></script>
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
	</script>
@endsection