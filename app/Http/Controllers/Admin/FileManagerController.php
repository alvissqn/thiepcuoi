<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Permission;

class FileManagerController extends Controller{

	/*
	 * Trang quản lý file
	 */
	public function index(){
		$data = [];
		return view('pages.admin.file-manager', $data);
	}

	/*
	 * Trang quản lý file
	 */
	public function test(){
		Permission::required('file_manager');
		$data = [];
		return view('pages.admin.file-manager', $data);
	}
}