<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;

class Auther extends Controller
{
    //загрузка страницы
    public function index(){
        return view('auth.login');
    }
    //получение данных из инпутов и открыть сессию
    public function store(Request $request){
        //сообщение об ошибках
        $messages = [
            'login.required' => 'Введите логин',
            'Password.required'  => 'Введите пароль',
        ];
        //валидация значений
        $credentials = $request->validate([
            'login' => [
                'required',
                'string',
            ],
            'Password' => [
                'required',
                'string',
            ],
        ], $messages);

        $user = User::where('login', $credentials['login'])->first();

        if (!$user || !Hash::check($credentials['Password'], $user->password)) {
            return back()->withErrors([
                'login' => 'Неправильный логин или пароль',
            ]);
        }

        if ($user->account_status_id === 2) {
            return back()->withErrors([
                'login' => 'Данный аккаунт не имеет доступа в систему',
            ]);
        }

        Auth::login($user);
        //сделать проверку ролей

        if ($user->role_id === 1) {
            return redirect()->route('postavshik.index');
        } elseif ($user->role_id === 2) {
            return redirect()->route('potrebitel.index');
        }
        elseif ($user->role_id === 3){
            return redirect()->route('prodavec.index');
        }
        elseif ($user->role_id === 4){
            return redirect()->route('meneger.index');
        }
        elseif ($user->role_id === 5){
            return redirect()->route('administrator.index');
        }
        //проверка сотрудников
    }  

    public function logout(){
        Auth::logout();
        return redirect()->route('home');
    }
}