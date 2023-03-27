@php
	$links = [
		'profile' => [
			'label' => __('user/profile.account_profile_heading_title'),
			'icon'  => 'bx-user'
		],
		'change-password' => [
			'label' => __('user/profile.account_change_password_heading_title'),
			'icon'  => 'bx-lock'
		],
		'change-avatar' => [
			'label' => __('user/profile.account_change_avatar_heading_title'),
			'icon'  => 'bx-image'
		],
		'notifications' => [
			'label' => __('user/profile.account_notifications_heading_title'),
			'icon'  => 'bx-bell'
		],
		'logs' => [
			'label' => __('user/profile.account_logs_heading_title'),
			'icon'  => 'bx-history'
		],
	];
@endphp
<div class="list-group list-group-borderless list-group-box-shadow">
	@foreach($links as $link => $item)
		<a class="list-group-item list-group-item-action {{ $active == $link ? 'active' : '' }}" href="{{ $link }}">
			<i class="bx bx-icon mr-50 {{ $item['icon'] }} align-top"></i>
			{{ $item['label'] }}
		</a>
	@endforeach
</div>