<?php
namespace App\Services;

use App\UserData;

class UserDataServices
{

	/*
	 * Lưu dữ liệu
	 */
	public static function update($data = [], $userId = null){
		$userId = $userId ?? \Auth::id();
		if( empty($userId) ){
			return false;
		}
		foreach($data as $key => $value){
			UserData::updateOrCreate(
				[
					'user_id' => $userId,
					'key'     => $key
				],
				[
					'user_id' => $userId,
					'key'     => $key,
					'value'   => $value
				]
			);
		}
	}

	/*
	 * Lấy dữ liệu của tài khoản
	 */
	public static function get($key = null, $userId = null){
		$userId = $userId ?? \Auth::id();
		if( $key ){
			return UserData::select('value')->where('user_id', $userId)->where('key', $key)->value('value');
		}else{
			$data = new \stdClass();
			foreach(UserData::select('key', 'value')->where('user_id', $userId)->get() as $item){
				$data->{$item->key} = $item->value;
			}
			return $data;
		}
	}
}
