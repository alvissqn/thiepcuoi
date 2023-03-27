<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Permission;

class HomeController extends Controller{

	/*
	 * Trang chính
	 */
	public function index(){
		$data['config'] = [
			'load_js_language' => [], // Các gói ngôn ngữ muốn chuyển sang JS, VD: ['user/register', 'user.general']
		];
		return view('/pages/home/index', $data);
	}

}