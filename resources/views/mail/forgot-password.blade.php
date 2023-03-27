@extends('mail.layout')
@section('body')
	<div>
		{!! __('mail.forgot_password', ['name' => $data->name, 'phone_number' => $data->phone_number, 'link' => route('user.resetPassword', $data->reset_password_key)]) !!}
	</div>
@endsection