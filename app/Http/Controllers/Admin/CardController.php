<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\UserTemplate;
use Illuminate\Http\Request;
use App\User;
use Permission, Auth;

class CardController extends Controller{
    public function index(){
        //Permission::required('admin');
        $data = new UserTemplate();
        return view('pages.admin.template.list-template',['data' => $data::all()]);
    }

    public function create(){
        $data = [];
        return view('pages.admin.template.add-template',['data' => $data]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'editor_thumbnail_template' => 'required|mimes:jpeg,png',
            'editor_file_template' => 'required|file|mimetypes:application/json,text/plain'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 404);
        }
        $template = new UserTemplate();
        $template->name = $request->editor_name_template;
        $name_json = vnStrFilter($request->editor_name_template).'.json';
        $thumbnail_path = $request->file('editor_thumbnail_template')->store('thumbnail_template');
        $file_json_path = $request->file('editor_file_template')->storeAs('json_template',$name_json);
        $template->link_json = $file_json_path;
        $template->thumbnail = $thumbnail_path;
        $template->userid = Auth::id();
        $template->save();
        return response()->json(['result' => 'Thêm Thiệp Thành Công','status_code' => 200]);
    }

}
