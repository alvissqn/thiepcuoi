@php
	use App\Helpers\Form;
@endphp
@extends('layouts.admin')
@section('header')
	@parent
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
				<div class="card-body">
					<section>
						<div class="row-detail row">
							<div style="width: 150px">
								{{ __('user/profile.account_edit_profile_field_registered_at') }}
							</div>
							<div style="width: calc(100% - 150px)">
								{{ Auth::user()->created_at->format( Option::get('settings__general_date_format') ) }}
							</div>
						</div>
						<div class="row-detail row">
							<div style="width: 150px">
								{{ __('user/profile.account_edit_profile_field_name') }}
							</div>
							<div style="width: calc(100% - 150px)">
								{{ Auth::user()->name }}
							</div>
						</div>
						<div class="row-detail row">
							<div style="width: 150px">
								{{ __('user/profile.account_edit_profile_field_phone_number') }}
							</div>
							<div style="width: calc(100% - 150px)">
								{{ Auth::user()->phone_number }}
							</div>
						</div>
						<div class="row-detail row">
							<div style="width: 150px">
								{{ __('user/profile.account_edit_profile_field_email') }}
							</div>
							<div style="width: calc(100% - 150px)">
								{{ Auth::user()->email }}
							</div>
						</div>
						<div class="row-detail row">
							<div style="width: 150px">
								{{ __('user/profile.account_edit_profile_field_gender') }}
							</div>
							<div style="width: calc(100% - 150px)">
								{{ __('user/general.gender_'. config('user.gender.text')[ Auth::user()->gender ] ) }}
							</div>
						</div>
						<div class="row-detail row">
							<div style="width: 150px">
								{{ __('user/profile.account_edit_profile_field_birth_day') }}
							</div>
							<div style="width: calc(100% - 150px)">
								{{ $userData->birth_day ?? null }}
							</div>
						</div>
						<div class="row-detail row">
							<div style="width: 150px">
								{{ __('user/profile.account_edit_profile_field_job_title') }}
							</div>
							<div style="width: calc(100% - 150px)">
								{{ $userData->job_title ?? null }}
							</div>
						</div>
						<div class="row-detail row">
							<div style="width: 150px">
								{{ __('user/profile.account_edit_profile_field_address') }}
							</div>
							<div style="width: calc(100% - 150px)">
								{{ $userData->address ?? null }}
							</div>
						</div>

					</section>
					<div class="text-center mt-2">
						<a class="btn btn-outline-primary" href="{{ route('admin.account.edit-profile') }}">
							{{ __('user/profile.update_profile_btn_label') }}
						</a>
					</div>
				</div>
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