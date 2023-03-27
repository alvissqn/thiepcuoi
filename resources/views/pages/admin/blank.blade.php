@extends(('layouts/Admin'))
{{-- Page title --}}
@section('title')
	Trang trống
	@parent
@stop
{{-- page level styles --}}
@section('header_styles')
	<link type="text/css" rel="stylesheet" href="{{asset('vendors/chartist/css/chartist.min.css')}}" />
	<link type="text/css" rel="stylesheet" href="{{asset('vendors/circliful/css/jquery.circliful.css')}}">
	<link type="text/css" rel="stylesheet" href="{{asset('css/pages/index.css')}}">
@stop
@section('content')

	<header class="head">
		<div class="main-bar">
			<div class="row no-gutters">
				<div class="col-6">
					<h4 class="m-t-5">
						<i class="fa fa-home"></i>
						Dashboard
					</h4>
				</div>
			</div>
		</div>
	</header>
	<main class="outer">
		<section class="inner bg-container">

		</section>
	</main>
@stop
@section('footer_scripts')
	<!--  plugin scripts -->
	<script type="text/javascript" src="{{asset('vendors/countUp.js/js/countUp.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('vendors/flip/js/jquery.flip.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/pluginjs/jquery.sparkline.js')}}"></script>
	<script type="text/javascript" src="{{asset('vendors/chartist/js/chartist.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/pluginjs/chartist-tooltip.js')}}"></script>
	<script type="text/javascript" src="{{asset('vendors/swiper/js/swiper.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('vendors/circliful/js/jquery.circliful.js')}}"></script>
	<script type="text/javascript" src="{{asset('vendors/flotchart/js/jquery.flot.js')}}" ></script>
	<script type="text/javascript" src="{{asset('vendors/flotchart/js/jquery.flot.resize.js')}}"></script>
	<!--end of plugin scripts-->

	<script type="text/javascript" src="{{asset('js/pages/index.js')}}"></script>
@stop
