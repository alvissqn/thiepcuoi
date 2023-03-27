@php
	use App\Helpers\Form;
@endphp
@extends('layouts.admin')
@section('header')
	@parent
	<script src="/assets/plugins/item-list/scripts.js"></script>
@endsection

@section('content')
	<main class="row">
		<section class="col-lg-4 mt-2">
			@include('pages.admin.account.includes.navbar-menu', ['active' => 'logs'])
		</section>
		<section class="col-lg-8 mt-2">
			<div class="card">
				<h4 class="card-header">
					{{ __('user/profile.account_logs_heading_title') }}
				</h4>
				<hr>
				<section class="card-body card-body-table" id="item-list">
					<div class="table-responsive table-responsive-v">
						<table class="table table-hover">
							<thead>
								<tr>
									<th style="width: 60px">
										#
									</th>
									<th>
										{{ __('admin/tools/logs.table_thead_action') }}
									</th>
									<th style="width: 180px">
										{{ __('admin/tools/logs.table_thead_time') }}
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
													{{ dateText($item->created_at->timestamp, Option::get('settings__general_time_format') ) }}
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