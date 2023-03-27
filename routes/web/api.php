<?php
Route::group(['prefix' => 'web-api'], function(){

	// Tìm kiếm tài khoản
	Route::post('get-users', [
		'as'   => 'webAPI.getUsers',
		'uses' => 'WebAPI\GetUsersController@getUsers'
	]);

}); // Group: web-api