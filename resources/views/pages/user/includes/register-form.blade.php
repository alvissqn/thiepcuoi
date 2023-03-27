@php
	use App\Helpers\Form;
	$user_register_data = session('user_register_data');
@endphp
@if( empty($user_register_data) )
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
@else
	<div class="text-center">
		<div>
			<img src="{{ $user_register_data['avatar'] }}" class="round" style="width: 60px; height: 60px">
		</div>
		<div>
			<b>
				{{ $user_register_data['name'] }}
			</b>
		</div>
	</div>
		<script type="text/javascript">
			$(document).ready(function(){
				setTimeout(function(){
					var input_phone_number = $('#user-register-form input[name="phone_number"]');
					input_phone_number.focus();
					input_phone_number.prev().addClass('input-label-has-content input-label-focus');
				}, 500);
			});
		</script>
@endif
@csrf()
<div class="mt-2"></div>
<div class="mb-2 {{ empty($user_register_data['name']) ? '' : 'hide' }}">
	{!!
		Form::text([
			'title'         => '',
			'placeholder'   => __('user/register.field_full_name'),
			'name'          => 'name',
			'value'         => session('user_register_data')['name'] ?? '',
			'class'         => 'round',
			'attr'          => '',
			'icon'          => 'bx-user',
			'icon_position' => 'right'
		])
	!!}
</div>
<div class="mb-2">
	{!!
		Form::text([
			'title'         => '',
			'placeholder'   => __('user/register.field_email_address'),
			'name'          => 'email',
			'value'         => session('user_register_data')['email'] ?? '',
			'class'         => 'round'.(isset($user_register_data) && empty($user_register_data['email']) ? ' is-invalid' : ''),
			'attr'          => '',
			'icon'          => 'bx-envelope',
			'icon_position' => 'right'
		])
	!!}
</div>
<div class="mb-2 {{ empty($user_register_data['phone_number']) ? '' : 'hide' }}">
	{!!
		Form::text([
			'title'         => '',
			'placeholder'   => __('user/register.field_phone_number'),
			'name'          => 'phone_number',
			'value'         => session('user_register_data')['phone_number'] ?? '',
			'class'         => 'round'.(isset($user_register_data) && empty($user_register_data['phone_number']) ? ' is-invalid' : ''),
			'attr'          => '',
			'icon'          => 'bx-phone bx-rotate-270',
			'icon_position' => 'right'
		])
	!!}
</div>
<div class="mb-2 {{ empty($user_register_data['password']) ? '' : 'hide' }}">
	{!!
		Form::password([
			'title'         => '',
			'placeholder'   => __('user/register.field_password'),
			'name'          => 'password',
			'value'         => session('user_register_data')['password'] ?? '',
			'class'         => 'round',
			'attr'          => '',
			'icon'          => 'bx-lock-alt',
			'icon_position' => 'right'
		])
	!!}
</div>
<div class="mb-2 {{ empty($user_register_data['password']) ? '' : 'hide' }}">
	{!!
		Form::password([
			'title'         => '',
			'placeholder'   => __('user/register.field_retyping_password'),
			'name'          => 'password_confirmation',
			'value'         => session('user_register_data')['password'] ?? '',
			'class'         => 'round',
			'attr'          => '',
			'icon'          => 'bx-lock-alt',
			'icon_position' => 'right'
		])
	!!}
</div>
<div class="form-group position-relative mb-2 {{ empty($user_register_data['name']) ? '' : 'hide' }}">
	<div class="row">
		<div class="col-8 align-self-center">
			<div class="mb-0">
				{!!
					Form::text([
						'title'         => '',
						'placeholder'   => __('user/register.field_captcha'),
						'name'          => 'captcha',
						'value'         => '',
						'class'         => 'round',
						'attr'          => '',
						'icon'          => '',
						'icon_position' => 'right'
					])
				!!}
			</div>
		</div>
		<div class="col-4 align-self-center">
			<img class="form-captcha" src="{{ route('captcha') }}">
		</div>
	</div>
</div>
<div class="form-notify hide alert alert-danger mb-1 mt-1"></div>
<button type="button" class="btn btn-primary glow position-relative w-100 round mt-1" onclick="userRegisterSubmit(this)">
	{{ empty($user_register_data['name']) ? __('user/register.submit_label') : __('user/register.submit_label_complete') }}
	<i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
</button>