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

    /*
     * Trình sửa ảnh
     */
    public function editor_image(){
        $data =[];
        return view('pages.admin.tools.editor-image',$data);
    }

    public function save_json_editor(Request $request){
        dd($request->filename);
//        $filename = $request->filename .'.json';
//        $json = stripslashes($request->json);
//        $upload_path = public_path('files/template/');
//        $upload_file = file_put_contents( $upload_path . $filename, $json );
//        return response()->json($upload_file,'200');
    }
}
