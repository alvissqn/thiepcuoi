<?php

return [

	// Số (phút) của phiên đăng nhập tài khoản
	'login_session_time' => 60 * 24 * 365, // 1 năm = 60 * 24 * 365

	// Khóa đăng nhập tài khoản nếu sai
	'block_login_failed_times' => 10, // Số lần
	'block_login_failed_seconds' => 10, // Thời gian khóa (phút)

	// Thư mục chứa ảnh avatar của tài khoản
	'avatars_path'       => '/files/users/avatars/',

	// Ảnh avatar mặc định
	'default_avatar' => '/files/users/avatars/default.png',

	// Mã bảo mật thêm vào mật khẩu (Nếu thay đổi thì các tài khoản cũ sẽ không login được)
	'salt_password'      => 'KiemCoder',

	// Giới tính
	'gender' => [
		'text' => [
			0 => 'unknown',
			1 => 'male',
			2 => 'female',
			3 => 'other'
		],
		'code' => [
			'unknown' => 0,
			'male'    => 1,
			'female'  => 2,
			'other'   => 3
		]
	],

	// Chức vụ
	'roles' => [
		'text' => [
			0 => 'unknown',
			1 => 'admin',
			2 => 'member'
		],
		'code' => [
			'unknown' => 0,
			'admin'   => 1,
			'member'  => 2
		]
	],
	
	// Trạng thái tài khoản
	'status' => [
		'text' => [
			0 => 'Tạm khóa',
			1 => 'Hoạt động'
		],
		'code' => [
			'blocked' => 0,
			'actived' => 1
		]
	],
];