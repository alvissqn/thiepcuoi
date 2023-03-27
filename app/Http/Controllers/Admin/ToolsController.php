<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Permission;

class ToolsController extends Controller{

	/*
	 * Trang CSS demo
	 */
	public function cssDemo(){
		Permission::required('admin');
		$data = [];
		return view('pages.admin.tools.css-demo', $data);
	}

	/*
	 * Lịch sử thao tác
	 */
	public function logs(){
		Permission::required('admin');
		$data = [];
		return view('pages.admin.tools.logs', $data);
	}
}