<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Meneger extends Controller
{
    //загрузка страницы
    public function index(){
        return view('meneger.mains');
    }
}
