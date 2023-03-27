<?php
namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserServices;
use Auth, Permission;

class GetUsersController extends Controller{

	/*
	 * Lấy danh sách tài khoản
	 */
	public function getUsers(Request $request){
		Permission::required('user_manager');
		$data = \App\User::select('id', 'name', 'phone_number', 'email');
		if( !empty($request->role) ){
			$data = $data->whereIn('role_id', $request->role_id);
		}
		if( !empty($request->keyword) ){
			$data = $data->where(function($query) use ($request) {
				foreach(['name', 'email', 'phone_number'] as $findBy){
					$query->orWhere($findBy, 'LIKE', "%{$request->keyword}%");
				}
			});
		}
		$data = $data->limit($request->limit ?? 10)->get();
		$out = [];
		foreach($data as $id => $item){
			$out[ $id ] = $item;
			$out[ $id ]->avatar = UserServices::avatar($item->email);
		}
		return response()->json($out, 200);
	}

}