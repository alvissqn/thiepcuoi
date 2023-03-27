<?php
Route::group(['prefix' => 'user'], function() {

	// Đăng ký
	Route::group(['prefix' => 'register'], function(){

		// Trang đăng ký
		Route::get('', [
			'as'   => 'user.register',
			'uses' => 'User\RegisterController@register'
		]);

		// Tạo tài khoản
		Route::post('', [
			'as'   => 'user.register.createAccount',
			'uses' => 'User\RegisterController@createAccount'
		]);

	}); // Group: register

	// Đăng nhập
	Route::group(['prefix' => 'login'], function(){

		// Trang đăng nhập
		Route::get('', [
			'as'   => 'user.login',
			'uses' => 'User\LoginController@login'
		]);

		// Ấn nút đăng nhập
		Route::post('', [
			'as'   => 'user.loginSubmit',
			'uses' => 'User\LoginController@loginSubmit'
		]);

		// Đăng nhập qua Facebook, Google
		Route::get('login-with-social/{service}', [
			'as'   => 'user.login.login-with-social',
			'uses' => 'User\LoginController@loginWithSocial'
		]);

		// Trang đăng nhập thành công qua Facebook, Google
		Route::get('login-with-social-callback/{service}', [
			'as'   => 'user.login.login-with-social-callback',
			'uses' => 'User\LoginController@loginWithSocialCallback'
		]);

	}); // Group: login

	// Quên mật khẩu
	Route::group(['prefix' => 'forgot-password'], function(){

		// Trang lấy lại mật khẩu
		Route::get('', [
			'as'   => 'user.forgotPassword',
			'uses' => 'User\ForgotPasswordController@forgotPassword'
		]);

		// Ấn nút lấy lại mật khẩu
		Route::post('', [
			'as'   => 'user.forgotPasswordSubmit',
			'uses' => 'User\ForgotPasswordController@forgotPasswordSubmit'
		]);

		// Khôi phục mật khẩu
		Route::get('reset-password/{key}', [
			'as'   => 'user.resetPassword',
			'uses' => 'User\ForgotPasswordController@resetPassword'
		]);

		// Ấn nút đổi lại mật khẩu	
		Route::post('reset-password', [
			'as'   => 'user.resetPasswordSubmit',
			'uses' => 'User\ForgotPasswordController@resetPasswordSubmit'
		]);	

	}); // Group: forgot-password

	// Đăng nhập nhanh qua key
	Route::get('login-with-private-key/{key}', [
		'as'   => 'user.resetPasswordSubmit',
		'uses' => 'User\LoginWithPrivateKeyController@loginWithPrivateKey'
	]);


	// Trang đăng xuất
	Route::get('logout', [
		'as'   => 'user.logout',
		'uses' => 'User\LogoutController@logout'
	]);
	
	// Thông báo
	Route::group(['prefix' => 'notification'], function(){

		// Lấy danh sách thông báo của tài khoản đang đăng nhập
		Route::get('get-my-notification', [
			'as'   => 'user.getMyNotification',
			'uses' => 'User\NotificationController@getMyNotification'
		]);

		//Đánh dấu là đã đọc
		Route::post('readed-notification', [
			'as'   => 'user.readedNotification',
			'uses' => 'User\NotificationController@readedNotification'
		]);

	}); // Group: notification

}); // Group: user