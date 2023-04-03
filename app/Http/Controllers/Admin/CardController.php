<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User;
use Permission, Auth;

class CardController extends Controller{
    public function index(){
        Permission::required('admin');
        $data = [];
        return view('pages.admin.template.list-template',$data);
    }
}
