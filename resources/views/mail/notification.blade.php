@extends('mail.layout')
@section('body')
	<div>
		{!! __('mail.notification', [
				'title'   => $data['title'],
				'content' => $data['content'],
			])
		!!}
	</div>
@endsection