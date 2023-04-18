<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Permission, Auth;

class CardController extends Controller{
    public function index(){
        //Permission::required('admin');
        $data = [];
        return view('pages.admin.tools.logs',$data);
    }
}
