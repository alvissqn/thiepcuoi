<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
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

}
