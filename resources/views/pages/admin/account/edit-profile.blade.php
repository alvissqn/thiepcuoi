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
			@include('pages.admin.account.includes.navbar-menu', ['active' => 'profile'])
		</section>
		<section class="col-lg-8 mt-2">
			<div class="card">
				<h4 class="card-header">
					{{ __('user/profile.account_profile_heading_title') }}
				</h4>
				<hr>
				<form class="card-body" method="POST" id="edit-profile-form">
					<section class="row">
						<input type="hidden" name="id" value="{{ Auth::id() }}">
						<div class="col-lg-6">
							<div class="mb-2">
								{!!
									Form::text([
										'title'         => '',
										'placeholder'   => __('user/profile.account_edit_profile_field_name'),
										'name'          => 'name',
										'value'         => Auth::user()->name,
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
									Form::text([
										'title'         => '',
										'placeholder'   => __('user/profile.account_edit_profile_field_phone_number'),
										'name'          => 'phone_number',
										'value'         => Auth::user()->phone_number,
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
									Form::text([
										'title'         => '',
										'placeholder'   => __('user/profile.account_edit_profile_field_email'),
										'name'          => 'email',
										'value'         => Auth::user()->email,
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
									Form::select2([
										'title'         => __('user/profile.account_edit_profile_field_gender'),
										'placeholder'   => '',
										'name'          => 'gender',
										'class'         => '',
										'attr'          => '',
										'icon'          => '',
										'icon_position' => 'right',
										'options'       => ( call_user_func(function(){
											foreach(config('user.gender.text') as $name => $label){
												$out[$name] = __('general.gender_'.$label);
											}
											return $out;
										}) ),
										'selected'      => [Auth::user()->gender],
										'multiple'      => false,
										'search'        => false
									])
								!!}
							</div>
						</div>
						<div class="col-lg-6 mb-2">
							{!!
								Form::datePicker([
									'placeholder' => __('user/profile.account_edit_profile_field_birth_day'),
									'name'        => 'birth_day',
									'value'       => $userData->birth_day ?? null,
									'position'    => 'bottom',
									'format'      => 'day/month/year',
									'config'      => [
										'allow'=>[
											'hours' => [],
											'minutes' => false,
											'days' => '1-31',
											'months' => '1-12',
											'weekDay' => [],
											'min' => ['y' => date('Y') - 50, 'm' => 1, 'd' => 1],
											'max' => ['y' => date('Y'), 'm' => date('m'), 'd' => date('d')]
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
						<div class="col-lg-6">
							<div class="mb-2">
								{!!
									Form::text([
										'title'         => '',
										'placeholder'   => __('user/profile.account_edit_profile_field_job_title'),
										'name'          => 'job_title',
										'value'         => $userData->job_title ?? null,
										'class'         => '',
										'attr'          => '',
										'icon'          => '',
										'icon_position' => 'right'
									])
								!!}
							</div>
						</div>
						<div class="col-lg-12 mb-2">
							<div>
								{!!
									Form::textarea([
										'title'       => '',
										'placeholder' => __('user/profile.account_edit_profile_field_address_note'),
										'name'        => 'address',
										'value'       => $userData->address ?? null,
										'rows'        => 4,
										'class'       => '',
										'attr'        => '',
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
						element: '#edit-profile-form',
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