<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Permission;

class IndexController extends Controller{
	/*
	 * Trang chính
	 */
	public function index(){
		Permission::required('member');
		$data = [];
		return view('pages.admin.index', $data);
	}
}