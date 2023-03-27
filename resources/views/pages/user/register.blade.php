@extends('layouts.default')
@section('header')
@parent
    <link rel="stylesheet" type="text/css" href="/assets/pages/user/authentication.css">
@endsection

@section('content')
	<!-- register section starts -->
	<section class="row account-form-layout">
		<div class="col-12 mt-4 mb-4">
			<div class="card bg-authentication mb-0">
				<div class="row m-0">
					<!-- register section left -->
					<div class="col-md-6 col-12 px-0">
						<div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
							<div class="card-header pb-1">
								<div class="card-title">
									<h4 class="text-center">{{ __('user/register.heading_title') }}</h4>
								</div>
							</div>
							<div class="text-center">
								<p>
									<small>{{ __('user/register.heading_description') }}</small>
								</p>
							</div>
							<div class="card-content">
								<div class="card-body">
									<form method="POST" id="user-register-form">
										@include('pages.user.includes.register-form')
									</form>
									<hr>
									<div class="text-center">
										<small class="mr-25">{{ __('user/register.have_account') }}</small><a
											href="login"><small>{{ __('user/register.login_link_title') }}</small> </a>
									</div>
									<div class="text-center mt-1">
										<a href="/">
											<i class="bx bx-chevron-left"></i>
											{{ __('user/register.back_to_home') }}
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- image section right -->
					<div class="col-md-6 d-md-block d-none text-center align-self-center p-3">
						<img class="img-fluid" src="/assets/images/pages/register.png" alt="Branding logo">
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- register section endss -->
@endsection


@section('footer-assets')
	@parent
	<script type="text/javascript" src="/assets/pages/user/register.js"></script>
	<script src="/assets/plugins/tooltip/tooltip.min.js"></script>
	<script type="text/javascript">
		$('[data-toggle="tooltip"]').tooltip()
	</script>
@endsection