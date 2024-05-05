<?php

namespace App\Http\Controllers;

use App\MyCompany;
use Illuminate\Http\Request;

class MyCompanyController extends Controller
{
    public function index(){
        return view('backend.my-company');
    }
    public function store(){
        $mc = null;
        return view('backend.my-company-store',compact('mc'));
    }
    public function update(MyCompany $mc){
        
        return view('backend.my-company-store',compact('mc'));
    }
}
