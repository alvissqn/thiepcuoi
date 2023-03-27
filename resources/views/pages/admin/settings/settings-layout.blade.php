@extends('layouts.admin')
@section('header')
	@parent
	<link rel="stylesheet" type="text/css" href="/assets/pages/admin/settings/style.css">
@endsection

@section('content')
	<form id="settings-form" action="{{ route('admin.settings.update') }}">
		@include('pages.admin.settings.includes.'.$include)
	</form>
@endsection

@section('footer')
	@parent
@endsection

@section('footer-assets')
	@parent
	<!-- Page JS-->
	<script src="/assets/pages/admin/settings/scripts.js"></script>
	<!-- /Page JS-->
@endsection