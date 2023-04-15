<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\UserTemplate;
//use Permission;

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

    /*
     * Trình sửa ảnh
     */
    public function editor_image(){
        $data = new UserTemplate;
        return view('pages.admin.tools.editor-image',['data' => $data::all()]);
    }

    public function save_json_editor(Request $request){
        $filename = $request->name .'.json';
        $json = stripslashes($request->json);
        $upload_path = '/files/template/';
        $upload_file = file_put_contents( public_path($upload_path.$filename), $json );

        $template = new UserTemplate();
        $template->name = $filename;
        $template->link_json = $upload_path.$filename;
        $template->save();
        return response()->json(['result'=>"thành công",'status_code'=>'200']);
    }

    public function save_image_library(Request $request){
        return $request->file('file');
//        $path = $request->file('file')->store('avatars');

//        return $path;
    }

}
