<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class Potrebitel extends Controller
{
    //загрузка страницы
    public function index(){
        return view('potrebitel.mains');
    }
}
