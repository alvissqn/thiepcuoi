@php
	use App\Helpers\Form;
@endphp
@extends('layouts.default')
@section('header')
@parent
    <link rel="stylesheet" type="text/css" href="/assets/pages/user/authentication.css">
@endsection

@section('content')
	<section class="row flexbox-container account-form-layout">
	    <div class="col-12 mt-4 mb-4">
	        <div class="card bg-authentication mb-0">
	            <div class="row m-0">
	                <!-- left section-reset password -->
	                <div class="col-md-6 col-12 px-0">
	                    <div class="card disable-rounded-right mb-0 p-2">
	                        <div class="card-header pb-1">
	                            <div class="card-title">
	                                <h4 class="text-center">
	                                	{{ __('user/reset-password.heading_title') }}
	                                </h4>
	                            </div>
	                        </div>
	                        <div class="card-content">
	                            <div class="card-body">
	                                <div class="text-muted text-center mb-2">
	                                	<small>
	                                		{{ __('user/reset-password.heading_description') }}
	                               		</small>
	                            	</div>
	                                <form class="mb-2" method="POST" id="user-reset-password-form">
	                                	@csrf
	                                	<input type="hidden" name="reset_password_key" value="{{ request()->route()->parameters['key'] }}">
	                                	<div class="mt-2">
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
	                                	<div class="mt-2">
											{!!
												Form::password([
													'title'         => '',
													'placeholder'   => __('user/register.field_retyping_password'),
													'name'          => 'password_confirmation',
													'value'         => '',
													'class'         => 'round',
													'attr'          => '',
													'icon'          => 'bx-lock-alt',
													'icon_position' => 'right'
												])
											!!}
										</div>
	                                	<div class="form-notify hide alert alert-danger mb-1 mt-1"></div>
	                                	<button type="button" class="btn btn-primary glow position-relative w-100 round mt-1" onclick="userResetPasswordSubmit(this)">
	                                		{{ __('user/reset-password.submit_label') }}
	                                		<i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
	                                	</button>
                                        <div class="form-group d-flex justify-content-between mb-2 mt-2">
                                        	<div class="text-left">
                                        		<div class="ml-3 ml-md-2 mr-1">
                                        			<a href="login">
                                        				{{ __('user/login.submit_label') }}
                                        			</a>
                                        		</div>
                                        	</div>
                                        	<div class="mr-3">
                                        		<a href="register">
                                        			{{ __('user/register.submit_label') }}
                                        		</a>
                                        	</div>
                                        </div>
	                                </form>
	                                <div class="text-center mt-2">
										<a href="/">
											<i class="bx bx-chevron-left"></i>
											{{ __('user/register.back_to_home') }}
										</a>
									</div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <!-- right section image -->
	                <div class="col-md-6 d-md-block d-none text-center align-self-center">
	                    <img class="img-fluid" src="/assets/images/pages/reset-password.png"
	                        alt="branding logo" width="300">
	                </div>
	            </div>
	        </div>
	    </div>
	</section>
@endsection

@section('footer')
@endsection

@section('footer-assets')
	@parent
	<script type="text/javascript" src="/assets/pages/user/reset-password.js"></script>
	<script src="/assets/plugins/tooltip/tooltip.min.js"></script>
	<script type="text/javascript">
		$('[data-toggle="tooltip"]').tooltip()
	</script>
@endsection