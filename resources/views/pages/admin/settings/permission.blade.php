@php
	use App\Helpers\Form;	
	use App\Role as RoleModel;
	use App\Services\RoleServices;
	$getRoles = RoleModel::paginate( paginationLimit() );
@endphp
@extends('layouts.admin')
@section('header')
	@parent
	<script src="/assets/plugins/form-ajax-save/scripts.js"></script>
	<script src="/assets/plugins/item-list/scripts.js"></script>
@endsection

@section('content')
	<main class="row">
		<section class="mt-2 col-md-6">
			<div class="card">
				<div class="card-header pt-2 row align-items-center">
					<div class="col-md-6">
						<h4 class="card-title">
							{{ __('admin/permission.role_heading_title') }}
						</h4>
					</div>
					<div class="col-md-6 justify-content-end text-right">
						<button type="button" class="btn btn-sm btn-primary" onclick="itemList.formInsertData('#modal-update-role', this, '{{ __('admin/permission.add_new_role') }}')">
							<i class="bx bx-plus"></i>
						</button>
					</div>
				</div>
				<hr>
				<div class="card-content collapse show">
					<div class="card-body">
						<div id="role-list">
							<div class="list-group">
								@foreach($getRoles as $item)
									<div class="list-group-item d-flex align-items-center p-1 item-list-row" data-id="{{ $item->id }}">
										<div class="col-7">
											<b style="color: {{ $item->color }}">
												{{ $item->name }}
											</b>
										</div>
										<div class="col-5 text-right">
											<button type="button" class="btn btn-sm btn-primary m-1px" onclick="itemList.formInsertData('#modal-set-permission', this, '{{ __('admin/permission.set_permission_for_', ['role_name' => $item->name]) }}')">
												<i class="bx bx-lock"></i>
											</button>
											<button type="button" class="btn btn-sm btn-primary m-1px" onclick="itemList.formInsertData('#modal-update-role', this, '{{ __('admin/permission.update_role') }}')">
												<i class="bx bx-edit"></i>
											</button>
											@php
												$item->permissions = RoleServices::getPermissionsByRoleId($item->id);
											@endphp
											<textarea class="hide item-json-data">{!! json_encode($item) !!}</textarea>
											@if( !$item->default )
												<button type="button" class="btn btn-sm btn-primary m-1px" onclick="itemList.formInsertData('#modal-delete-role', this, '{{ __('admin/permission.delete_role_', ['role_name' => $item->name]) }}'); deleteRole({{ $item->id }})">
													<i class="bx bx-x"></i>
												</button>
											@endif
										</div>
									</div>
								@endforeach
							</div>
							<div class="pagination-center">
								{!! $getRoles->links() !!}
							</div>
						</div>
					</div>
				</div>
			</div><!--/.card-->

			{!!
				\App\Services\LogServices::timeline([
					'title'      => __('admin/tools/logs.action_history_title'),
					'category' => 'permission',
					'limit'      => 50
				]);
			!!}
		</section>
		<section class="mt-2 col-md-6">
			<div class="card">
				<div class="card-header pt-2 row align-items-center">
					<div class="col-md-12">
						<h4 class="card-title">
							{{ __('admin/permission.permission_heading_title') }}
						</h4>
					</div>
				</div>
				<hr>
				<div class="card-content">
					<form class="card-body" method="POST" action="{{ route('admin.settings.permission.update-permission-name') }}">
						@foreach( config('storage.permissions_name') as $name => $label)
							<div class="position-relative mb-2">
								{!!
									Form::text([
										'title'         => '',
										'placeholder'   => $name,
										'group_name'    => '',
										'name'          => $name,
										'value'         => $label,
										'class'         => '',
										'attr'          => '',
										'icon'          => '',
										'icon_position' => ''
									])
								!!}
								<button onclick="$(this).parent().remove();" class="btn btn-sm btn-default" type="button" style="position: absolute; top: 10%; right: 5px; padding: 5px">
									<i class="bx bx-trash"></i>
								</button>
							</div>
						@endforeach
						<div class="text-center">
							<button type="submit" class="btn btn-primary">
								{{ __('admin/permission.permission_save_btn_label') }}
							</button>
						</div>
					</form>
				</div>
			</div>
		</section>
	</main>

	<!-- Thêm, sửa chức vụ -->
	<form class="modal fade" id="modal-update-role" tabindex="-1" role="dialog"
	 aria-hidden="true" method="POST" action="{{ route('admin.settings.permission.update-role') }}">
		<div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
		role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						{{ __('admin/permission.update_role') }}
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i class="bx bx-x"></i>
					</button>
				</div>
				<div class="modal-body custom-scrollbar">
					<input type="hidden" name="id">
					<div class="mb-1">
						{!!
							Form::text([
								'title'         => '',
								'placeholder'   => __('admin/permission.input_role_name'),
								'group_name'    => '',
								'name'          => 'name',
								'value'         => '',
								'class'         => '',
								'attr'          => '',
								'icon'          => '',
								'icon_position' => ''
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::colorPicker([
								'label'         => __('admin/permission.input_role_color'),
								'name'          => 'color',
								'value'         => '#FF00FF',
								'default'       => '',
								'required'      => true,
								'class'         => '',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left'
							])
						!!}
					</div>
					<div class="form-notify hide alert alert-danger"></div>
				</div>
				<div class="modal-footer justify-content-center">
					<div class="text-center">
						<button type="button" class="btn btn-primary form-save">
							{{ __('admin/permission.update_role_btn_label') }}
						</button>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			formAjaxSave.init({
				element: '#modal-update-role',
				success: function(){
					itemList.reload({
						element: '#role-list',
						formFilter: '',
						data: {page: 1},
						success: function(){

						}
					});
					$('.modal').modal('hide');
				}
			});
		</script>
	</form>

	<!-- Thiết lập quyền cho chức vụ -->
	<form class="modal fade" id="modal-set-permission" tabindex="-1" role="dialog"
	 aria-hidden="true" method="POST" action="{{ route('admin.settings.permission.set-permission-for-role') }}">
		<div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
		role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						{{ __('admin/permission.set_permission_title') }}
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i class="bx bx-x"></i>
					</button>
				</div>
				<div class="modal-body custom-scrollbar">
					<input type="hidden" name="id">
					<div class="row">
						@foreach( config('storage.permissions_name') as $name => $label)
							<div class="mb-2 col-md-6">
								{!!
									Form::switch([
										'label' => $label,
										'name'  => 'permissions[]',
										'value' => $name,
										'class' => '',
										'attr'  => ''
									])
								!!}
							</div>
						@endforeach
					</div>
					<div class="form-notify hide alert alert-danger"></div>
				</div>
				<div class="modal-footer justify-content-center">
					<div class="text-center">
						<button type="button" class="btn btn-primary form-save">
							{{ __('admin/permission.update_role_btn_label') }}
						</button>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			formAjaxSave.init({
				element: '#modal-set-permission',
				success: function(){
					itemList.reload({
						element: '#role-list',
						formFilter: '',
						data: {page: 1},
						success: function(){

						}
					});
					$('.modal').modal('hide');
				}
			});
		</script>
	</form>

	<!-- Xóa chức vụ -->
	<form class="modal fade" id="modal-delete-role" tabindex="-1" role="dialog"
	 aria-hidden="true" method="POST" action="{{ route('admin.settings.permission.delete-role') }}">
		<div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
		role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i class="bx bx-x"></i>
					</button>
				</div>
				<div class="modal-body custom-scrollbar">
					<input type="hidden" name="id">
					<div class="mb-2 mt-2">
						{!!
							Form::select2([
								'title'         => __('admin/permission.delete_role_move_to'),
								'name'          => 'move_to',
								'class'         => '',
								'attr'          => '',
								'icon'          => '',
								'icon_position' => 'right',
								'options' => call_user_func(function() use($getRoles) {
									$out = ['' => '--'];
									foreach($getRoles as $item){
										$out[$item->id] = $item->name;
									}
									return $out;
								}),
								'selected' => [''],
								'multiple' => false,
								'search'   => false
							])
						!!}
					</div>
					<div class="form-notify hide alert alert-danger"></div>
				</div>
				<div class="modal-footer justify-content-center">
					<div class="text-center">
						<button type="button" class="btn btn-danger form-save">
							{{ __('admin/permission.delete_role_btn_label') }}
						</button>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			formAjaxSave.init({
				element: '#modal-delete-role',
				success: function(){
					location.reload();
				}
			});
		</script>
	</form>
@endsection

@section('footer')
	@parent
@endsection

@section('footer-assets')
	@parent
	<script type="text/javascript">
		itemList.autoReload({
			element: '#role-list',
			formFilter: '.form-filter',
			data: {},
			timer: 20, // Số giây tự load lại
			success: function(){
				
			}
		});
		function deleteRole(id){
			$('#modal-delete-role select[name="move_to"]').find('option[value="'+id+'"], option[value="1"]').prop('disabled', true);
		}
	</script>
@endsection