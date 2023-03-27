@php
	use App\Helpers\Form;
@endphp
@csrf()
<div class="d-flex flex-md-row flex-column justify-content-around">
	<a href="{{ route('user.login.login-with-social', 'google') }}" class="btn btn-social btn-google btn-block font-small-3 mr-md-1 mb-md-0 mb-1">
		<i class="bx bxl-google font-medium-3"></i>
		<span class="pl-50 d-block text-center">Google</span>
	</a>
	<a href="{{ route('user.login.login-with-social', 'facebook') }}" class="btn btn-social btn-block mt-0 btn-facebook font-small-3">
		<i class="bx bxl-facebook-square font-medium-3"></i>
		<span class="pl-50 d-block text-center">Facebook</span>
	</a>
</div>
<div class="divider">
	<div class="divider-text text-uppercase text-muted">
		<small>
			{{ __('user/login.login_with_email') }}
		</small>
	</div>
</div>
<div class="mb-2">
	{!!
		Form::text([
			'title'         => '',
			'placeholder'   => __('user/register.field_email_address'),
			'name'          => 'email',
			'value'         => '',
			'class'         => 'round',
			'attr'          => '',
			'icon'          => 'bx-envelope',
			'icon_position' => 'right'
		])
	!!}
</div>
<div class="mb-2">
	{!!
		Form::password([
			'title'         => '',
			'placeholder'   => __('user/register.field_password'),
			'name'          => 'password',
			'value'         => '',
			'class'         => 'round',
			'attr'          => '',
			'icon'          => 'bx-lock-alt',
			'icon_position' => 'right'
		])
	!!}
</div>
<div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center mt-1">
	<div class="text-left">
		<div class="checkbox checkbox-sm">
			<input type="checkbox" class="form-check-input" id="user-login-keep-session" checked>
			<label class="" for="user-login-keep-session">
				<small>
					{{ __('user/login.keep_session') }}
				</small>
			</label>
		</div>
	</div>
	<div class="text-right">
		<a href="forgot-password" class="card-link" id="user-login-forgot-password">
			<small>{{ __('user/login.forgot_password') }}</small>
		</a>
	</div>
</div>
<div class="form-notify hide alert alert-danger mb-1 mt-1"></div>
<button type="button" class="btn btn-primary glow position-relative w-100 round mt-1" onclick="userLoginSubmit(this)">
	{{ __('user/login.submit_label') }}
	<i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
</button>
