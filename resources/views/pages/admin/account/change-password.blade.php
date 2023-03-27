@php
	use App\Helpers\Form;
@endphp
@extends('layouts.admin')
@section('header')
	@parent
	<script src="/assets/plugins/form-ajax-save/scripts.js"></script>
@endsection

@section('content')
	<main class="row">
		<section class="col-lg-4 mt-2">
			@include('pages.admin.account.includes.navbar-menu', ['active' => 'change-password'])
		</section>
		<section class="col-lg-8 mt-2">
			<div class="card">
				<h4 class="card-header">
					{{ __('user/profile.account_change_password_heading_title') }}
				</h4>
				<hr>
				<form class="card-body" method="POST" id="change-password-form">
					<section class="row">
						<div class="col-lg-12">
							<div class="mb-2">
								{!!
									Form::password([
										'title'         => '',
										'placeholder'   => __('user/profile.account_edit_profile_field_password_old'),
										'name'          => 'password_old',
										'value'         => '',
										'class'         => '',
										'attr'          => '',
										'icon'          => '',
										'icon_position' => 'right',
										'required'      => true
									])
								!!}
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-2">
								{!!
									Form::password([
										'title'         => '',
										'placeholder'   => __('user/profile.account_edit_profile_field_password'),
										'name'          => 'password',
										'value'         => '',
										'class'         => '',
										'attr'          => '',
										'icon'          => '',
										'icon_position' => 'right',
										'required'      => true
									])
								!!}
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-2">
								{!!
									Form::password([
										'title'         => '',
										'placeholder'   => __('user/profile.account_edit_profile_field_password_retyping'),
										'name'          => 'password_confirmation',
										'value'         => '',
										'class'         => '',
										'attr'          => '',
										'icon'          => '',
										'icon_position' => 'right',
										'required'      => true
									])
								!!}
							</div>
						</div>
					</section>
					<div class="form-notify alert alert-danger hide"></div>
					<div class="text-center">
						<button type="button" class="btn btn-primary form-save">
							{{ __('user/profile.update_profile_btn_label') }}
						</button>
					</div>
				</form>
				<script type="text/javascript">
					formAjaxSave.init({
						element: '#change-password-form',
						success: function(){
							location.href = "{{ route('admin.account.profile') }}";
						}
					});
				</script>
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