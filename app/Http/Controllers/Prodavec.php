<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Prodavec extends Controller
{
    //загрузка страницы
    public function index(){
        return view('prodavec.mains');
    }
}
