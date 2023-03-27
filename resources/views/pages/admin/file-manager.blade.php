@extends('layouts.admin')
@section('header')
	@parent
@endsection

@section('content')
	<main class="mt-2">
		<section class="card">
			<h4 class="card-header">
				{{ __('admin/file_manager.heading_title') }}
			</h4>
			<hr>
			<div class="card-body">
				@include('includes.file-manager')
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