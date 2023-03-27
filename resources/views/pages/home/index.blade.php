@extends('layouts.default')
@section('header')
@parent
@endsection

@section('content')

	<div class="card mt-5">
		<div class="card-header">
			<h3 class="card-title">Hello world</h3>
		</div>
		<div class="card-content">
			<div class="card-body">
				<p>
					{{ __('test.hello_world', ['name' => Auth::user()->name ?? null]) }}
				</p>
			</div>
		</div>
	</div>
@endsection

@section('footer')
@endsection

@section('footer-assets')
	@parent
@endsection