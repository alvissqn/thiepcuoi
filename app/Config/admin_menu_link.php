<?php
/*
 * Link menu bên trái của trang quản trị
 */

return [
	url('/') => [
		'label'            => 'Trang chủ',
		'icon'             => 'bx-home',
		'count'            => 0,
		'count_style'      => 'info',
		'hidden'           => false,
		'permission'       => 'member',
		'load_js_language' => []
	],
	'index' => [
		'label'            => 'Tổng quan',
		'icon'             => 'bxl-internet-explorer',
		'count'            => 1,
		'count_style'      => 'info',
		'hidden'           => false,
		'permission'       => 'member',
		'load_js_language' => []
	],
	'account' => [
		'label'            => 'Tài khoản của tôi',
		'icon'        => 'bx-user',
		'count'       => 0,
		'count_style' => 'primary',
		'permission'  => 'member',
		'hidden'      => true,
		'subs'        => [
			'profile' => [
				'label'            => 'Trang chủ',
				'count'            => 0,
				'count_style'      => 'warning',
				'hidden'           => false,
				'permission'       => 'member',
				'load_js_language' => []
			],
			'edit-profile' => [
				'label'            => 'Trang chủ',
				'count'            => 0,
				'count_style'      => 'warning',
				'hidden'           => false,
				'permission'       => 'member',
				'load_js_language' => []
			],
			'change-password' => [
				'label'            => 'Trang chủ',
				'count'            => 0,
				'count_style'      => 'warning',
				'hidden'           => false,
				'permission'       => 'member',
				'load_js_language' => []
			],
			'change-avatar' => [
				'label'            => 'Trang chủ',
				'count'            => 0,
				'count_style'      => 'warning',
				'hidden'           => false,
				'permission'       => 'member',
				'load_js_language' => []
			],
			'notifications' => [
				'label'            => 'Trang chủ',
				'count'            => 0,
				'count_style'      => 'warning',
				'hidden'           => false,
				'permission'       => 'member',
				'load_js_language' => []
			],
			'logs' => [
				'label'            => 'Trang chủ',
				'count'            => 0,
				'count_style'      => 'warning',
				'hidden'           => false,
				'permission'       => 'member',
				'load_js_language' => []
			],
		]
	],
	'users' => [
		'label'            => 'Tài khoản',
		'icon'        => 'bx-id-card',
		'count'       => 0,
		'count_style' => 'primary',
		'permission'  => 'user_manager',
		'hidden'      => false,
		'subs'        => [
			'list' => [
				'label'            => 'Danh sách người dùng',
				'count'            => 0,
				'count_style'      => 'warning',
				'hidden'           => false,
				'permission'       => 'user_manager',
				'load_js_language' => []
			],
			'notifications' => [
				'label'            => 'Thông báo',
				'count'            => 0,
				'count_style'      => 'warning',
				'hidden'           => false,
				'permission'       => 'user_manager',
				'load_js_language' => []
			],
		]
	],
	'file-manager' => [
		'label'            => 'Quản lý file',
		'icon'             => 'bx-folder-open',
		'count'            => 0,
		'count_style'      => 'info',
		'hidden'           => false,
		'permission'       => 'file_manager',
		'load_js_language' => []
	],
	'settings' => [
		'label'            => 'Cài đặt',
		'icon'        => 'bx-cog',
		'count'       => 0,
		'count_style' => 'primary',
		'permission'  => 'admin',
		'hidden'      => false,
		'subs'        => [
			'general' => [
				'label'            => 'Thiết lập chung',
				'count'            => 0,
				'count_style'      => 'warning',
				'hidden'           => false,
				'permission'       => 'admin',
				'load_js_language' => []
			],
			'theme' => [
				'label'            => 'Giao diện',
				'count'            => 0,
				'count_style'      => 'danger',
				'hidden'           => false,
				'permission'       => 'admin',
				'load_js_language' => []
			],
			'admin' => [
				'label'            => 'Trang quản trị',
				'count'            => 0,
				'count_style'      => 'danger',
				'hidden'           => false,
				'permission'       => 'admin',
				'load_js_language' => []
			],
			'language' => [
				'label'            => 'Ngôn ngữ',
				'count'            => 0,
				'count_style'      => 'danger',
				'hidden'           => false,
				'permission'       => 'admin',
				'load_js_language' => []
			],
			'page-info' => [
				'label'            => 'Thông tin trang',
				'count'            => 0,
				'count_style'      => 'danger',
				'hidden'           => false,
				'permission'       => 'admin',
				'load_js_language' => []
			],
			'permission' => [
				'label'            => 'Phân quyền',
				'count'            => 0,
				'count_style'      => 'danger',
				'hidden'           => false,
				'permission'       => 'admin',
				'load_js_language' => []
			]
		]
	],
	'tools' => [
		'label'            => 'Công cụ',
		'icon'        => 'bx-wrench',
		'count'       => 0,
		'count_style' => 'primary',
		'permission'  => 'admin',
		'hidden'      => false,
		'subs'        => [
			'logs' => [
				'label'            => 'Lịch sử thao tác',
				'count'            => 0,
				'count_style'      => 'warning',
				'hidden'           => false,
				'permission'       => 'admin',
				'load_js_language' => []
			],
			'css-demo' => [
				'label'            => 'CSS demo',
				'count'            => 0,
				'count_style'      => 'danger',
				'hidden'           => false,
				'permission'       => 'admin',
				'load_js_language' => []
			],
            'editor-image' => [
                'label'            => 'Thiết Kế Thiệp',
                'count'            => 0,
                'count_style'      => 'danger',
                'hidden'           => false,
                'permission'       => 'admin',
                'load_js_language' => []
            ],
		]
	],
    'template' => [
        'label'             => 'Mẫu Thiệp',
        'icon'          => 'bx-wrench',
        'count'         => 0,
        'count_style'   => 'primary',
        'permission'    => 'admin',
        'hidden'        => false,
        'subs'          => [
            'list-template' => [
                'label'     => 'Danh sách',
                'count'     => 0,
                'count_style' => 'warning',
                'hidden'    => false,
                'permission' => 'admin',
                'load_js_language' => []
            ],
            'add-template'  => [
                'label'     => 'Thêm mẫu thiệp',
                'count'     => 0,
                'count_style'   => 'warning',
                'hidden'    => false,
                'permission'    => 'admin',
                'load_js_language'  => []
            ],
        ]
    ],
];
