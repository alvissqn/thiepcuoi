<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\UserTemplate;
use App\LibraryMedia;
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
		//Permission::required('admin');
		$data = [];
		return view('pages.admin.tools.logs', $data);
	}

    /*
     * Trình sửa ảnh
     */
    public function editor_image(){
        $data = new UserTemplate;
        $temp_favorite = UserTemplate::where('favorite','=',1)->get();
        return view('pages.admin.tools.editor-image',['data' => $data::all(),'favorite' => $temp_favorite]);
    }

    public function save_json_editor(Request $request){
        $filename = vnStrFilter($request->name,'_') .'.json';
        $thubmnail_file = vnStrFilter($request->name,'_') .'.jpg';
        $json = stripslashes($request->json);
        $upload_path = '/files/template/';
        $thumbnail_path = '/files/thumbnail/';
        $upload_file = file_put_contents( public_path($upload_path.$filename), $json );
        $thumbnail_file = file_put_contents(public_path($thumbnail_path.$filename));
        $template = new UserTemplate();
        $template->name = $request->name;
        $template->link_json = $upload_path.$filename;
        $template->save();
        return response()->json(['result'=>"thành công",'status_code'=>'200']);
    }

    public function save_template_favorite(Request $request){
        $idTemplate = $request->templateid;
        if($request->status == 'remove'){
            UserTemplate::where('templateid',$idTemplate)->update(['favorite'=>0]);
            return response()->json(['result' => 'Hủy yêu thích thành công']);
        } else {
            UserTemplate::where('templateid',$idTemplate)->update(['favorite'=>1]);
            return response()->json(['result' => 'Thêm yêu thích thành công']);
        }
    }

    public function save_image_library(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpeg,png,svg',
        ]);
        $media = new LibraryMedia();
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 404);
        }
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $path = $file->storeas('library',$fileName);
        $media->filename = $fileName;
        $media->linkfile = $path;
        $media->filetype = $file->getClientOriginalExtension();
        $media->save();
        return response()->json(['result' => 'Upload Thành Công', 'path_image' => $path]);
    }

    public function getLibrary($iduser = null,$filetype = ""){
//        $path_library = '../storage/app/library/*';
//        $file_json = glob_recursive($path_library);
//        return response()->json(['result' => $file_json]);
       if(empty($filetype)){
           $data = LibraryMedia::all();
       } else {
           $data = LibraryMedia::Where('filetype','=',$filetype)->get();
       }
       return response()->json(['result' => $data]);
    }

    public function save_sticker(Request $request){

    }

}
