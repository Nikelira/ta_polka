<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Administrator extends Controller
{
    //загрузка страницы
    public function index(){
        return view('administrator.mains');
    }
}
