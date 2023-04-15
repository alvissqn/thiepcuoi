<?php
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function(){

	// Chuyển về trang chính
	Route::get('', function(){
		return redirect('/admin/index');
	});

	// Trang chính
	Route::get('index', [
		'as'   => 'admin.index',
		'uses' => 'Admin\IndexController@index'
	]);

	// Tài khoản
	Route::group(['prefix' => 'account'], function (){

		// Trang thông tin tài khoản
		Route::get('profile', [
			'as'   => 'admin.account.profile',
			'uses' => 'Admin\AccountController@profile'
		]);

		// Cập nhật thông tin tài khoản
		Route::group(['prefix' => 'edit-profile'], function(){

			// Trang cập nhật thông tin
			Route::get('', [
				'as'   => 'admin.account.edit-profile',
				'uses' => 'Admin\AccountController@editProfile'
			]);

			// Ấn nút cập nhật thông tin
			Route::post('', [
				'as'   => 'admin.account.edit-profile.submit',
				'uses' => 'Admin\AccountController@editProfileSubmit'
			]);

		}); // Group: change-avatar

		// Đổi mật khẩu
		Route::group(['prefix' => 'change-password'], function(){

			// Trang cập nhật thông tin
			Route::get('', [
				'as'   => 'admin.account.change-password',
				'uses' => 'Admin\AccountController@changePassword'
			]);

			// Ấn nút cập nhật thông tin
			Route::post('', [
				'as'   => 'admin.account.change-password.submit',
				'uses' => 'Admin\AccountController@changePasswordSubmit'
			]);

		}); // Group: change-password

		// Đổi avatar
		Route::group(['prefix' => 'change-avatar'], function(){

			// Trang thông tin tài khoản
			Route::get('', [
				'as'   => 'admin.account.change-avatar',
				'uses' => 'Admin\AccountController@changeAvatar'
			]);

			// Ấn nút cập nhật avatar
			Route::post('', [
				'as'   => 'admin.account.change-avatar.submit',
				'uses' => 'Admin\AccountController@changeAvatarSubmit'
			]);

			// Ấn nút xoay avatar
			Route::post('rotate', [
				'as'   => 'admin.account.change-avatar.rotate',
				'uses' => 'Admin\AccountController@changeAvatarRotate'
			]);

		}); // Group: change-avatar

		// Trang thông báo
		Route::get('notifications', [
			'as'   => 'admin.account.notifications',
			'uses' => 'Admin\AccountController@notifications'
		]);

		// Trang lịch sử hoạt động
		Route::get('logs', [
			'as'   => 'admin.account.logs',
			'uses' => 'Admin\AccountController@logs'
		]);

	}); // Group: account

	// Người dùng
	Route::group(['prefix' => 'users'], function (){

		// Danh sách tài khoản
		Route::group(['prefix' => 'list'], function(){

			// Trang danh sách
			Route::get('', [
				'as'   => 'admin.users.list',
				'uses' => 'Admin\UsersController@list'
			]);

			// Ấn nút thêm tài khoản
			Route::post('add-user', [
				'as'   => 'admin.users.add-user',
				'uses' => 'Admin\UsersController@addUser'
			]);

			// Ấn nút cập nhật thông tin tài khoản
			Route::post('update-profile', [
				'as'   => 'admin.users.update-profile',
				'uses' => 'Admin\UsersController@updateProfile'
			]);

			// Đặt mật khẩu cho 1 tài khoản
			Route::post('update-password', [
				'as'   => 'admin.users.update-password',
				'uses' => 'Admin\UsersController@updatePassword'
			]);

		}); // Group: list


		// Gửi thông báo đến các tài khoản
		Route::group(['prefix' => 'notifications'], function(){

			// Trang gửi thông báo
			Route::get('', [
				'as'   => 'admin.users.notifications',
				'uses' => 'Admin\UsersController@notifications'
			]);

			// Gửi thông báo đến người dùng
			Route::post('send-notification', [
				'as'   => 'admin.users.notifications.send',
				'uses' => 'Admin\UsersController@sendNotification'
			]);

			// Xóa thông báo
			Route::delete('delete-notification', [
				'as'   => 'admin.users.notifications.delete',
				'uses' => 'Admin\UsersController@deleteNotification'
			]);

		}); // Group: notifications

	}); // Group: users

	// Cài đặt
	Route::group(['prefix' => 'settings'], function(){

		// Lưu cài đặt
		Route::post('', [
			'as'   => 'admin.settings.update',
			'uses' => 'Admin\SettingsController@updateSettings'
		]);

		// Trang cài đặt chung
		Route::get('general', [
			'as'   => 'admin.settings.general',
			'uses' => 'Admin\SettingsController@generalSettings'
		]);

		// Trang cài đặt giao diện
		Route::get('theme', [
			'as'   => 'admin.settings.theme',
			'uses' => 'Admin\SettingsController@themeSettings'
		]);

		// Trang cài đặt trang quản lý
		Route::get('admin', [
			'as'   => 'admin.settings.settings',
			'uses' => 'Admin\SettingsController@adminSettings'
		]);

		// Chỉnh ngôn ngữ
		Route::group(['prefix' => 'language'], function (){
			// Trang thiết lập ngôn ngữ
			Route::get('', [
				'as'   => 'admin.settings.language.index',
				'uses' => 'Admin\SettingsController@language'
			]);

			// Cập nhật ngôn ngữ
			Route::post('',[
				'as'   => 'admin.settings.language.update',
				'uses' => 'Admin\SettingsController@updateLanguage'
			]);
		}); // Group: language

		// Chỉnh thông tin trang
		Route::group(['prefix' => 'page-info'], function (){

			// Trang sửa thông tin các trang
			Route::get('', [
				'as'   => 'admin.settings.page-info.index',
				'uses' => 'Admin\SettingsController@pageInfo'
			]);

			// Cập nhật thông tin các trang
			Route::post('', [
				'as'   => 'admin.settings.page-info.update',
				'uses' => 'Admin\SettingsController@updatePageInfo'
			]);

		}); // Group: page-info

		// Phân quyền & chức vụ
		Route::group(['prefix' => 'permission'], function(){

			// Trang chính
			Route::get('', [
				'as'   => 'admin.settings.permission.index',
				'uses' => 'Admin\SettingsController@permission'
			]);

			// Cập nhật tên quyền
			Route::post('update-permission-name', [
				'as'   => 'admin.settings.permission.update-permission-name',
				'uses' => 'Admin\SettingsController@updatePermissionsName'
			]);

			// Thêm hoặc sửa chức vụ
			Route::post('update-role', [
				'as'   => 'admin.settings.permission.update-role',
				'uses' => 'Admin\SettingsController@updateRole'
			]);

			// Xóa chức vụ
			Route::post('delete-role', [
				'as'   => 'admin.settings.permission.delete-role',
				'uses' => 'Admin\SettingsController@deleteRole'
			]);

			// Set quyền cho chức vụ
			Route::post('set-permission-for-role', [
				'as'   => 'admin.settings.permission.set-permission-for-role',
				'uses' => 'Admin\SettingsController@setPermissionsForRole'
			]);
		}); // Group: permission

	}); // Group: settings

	// Các công cụ
	Route::group(['prefix' => 'tools'], function (){

		// Css demo
		Route::get('css-demo', [
			'as'   => 'admin.tools.css-demo',
			'uses' => 'Admin\ToolsController@cssDemo'
		]);

		// Lịch sử thao tác
		Route::get('logs',[
			'as'   => 'admin.tools.logs',
			'uses' => 'Admin\ToolsController@logs'
		]);

        Route::get('editor-image',[
            'as' => 'admin.tools.editor',
            'uses' => 'Admin\ToolsController@editor_image'
        ]);

        Route::post('editor-savejson',[
           'as' => 'admin.tools.savejson',
           'uses' => 'Admin\ToolsController@save_json_editor'
        ]);

        Route::post('editor-savelibrary',[
            'as' => 'admin.tools.savelibrary',
            'uses' => 'Admin\Toolscontroller@save_image_library)'
        ]);
	}); // Group: tools

	// Quản lý file
	Route::group(['prefix' => 'file-manager'], function (){

		// Trang quản lý file
		Route::get('', [
			'as'   => 'admin.file-manager',
			'uses' => 'Admin\FileManagerController@index'
		]);

	}); // Group: file-manager

    Route::group(['prefix' => 'templates'],function(){

        Route::get('list-template',[
            'as' => 'admin.template',
            'uses' => 'Admin\CardController@index'
        ]);
    }); // Group: Template

}); // Group: admin
