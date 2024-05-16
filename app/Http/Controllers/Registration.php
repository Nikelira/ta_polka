<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Registration extends Controller
{
    //загрузка формы
    public function index(){
        return view('auth.reg');
    }
    //https://laravel.ru/docs/v5/validation
    //получить данные из инпутов и создать
    public function store(Request $request){
        //сообщение об ошибках
        $messages = [
            'login.required'  => 'Выберите другой логин',
            'password.required'  => 'Пароль должен совпадать',
            'agree.required'  => 'Необходимо подтвердить согласие на обработку данных',
        ];
        //валидация значений
        $request->validate([
            'surname' => 'required|string|max:50',
            'name' => 'required|string|max:50',
            'Patronymic' => 'required|string|max:50',
            'login'=> ['required', 'string', 'alpha_dash', 'unique:users'],
            'password'=> 'required|confirmed',
            'agree' => ['required', 'accepted'],
        ], $messages);

        $hashedPassword = Hash::make($request->password);
        
        //создаем нового пользователя в бд
        $user = User::create([
            'surname' => $request->surname,
            'name' => $request->name,
            'Partonymic' => $request->Partonymic,
            'login' => $request->login,
            'password' => $hashedPassword,
            'role_id' => $request->role,
            'account_status'=>1,
        ]);

        //проверка роли
        if ($user->role_id == 1) {
            Auth::login($user);
            return redirect()->route('postavshik.index');
        } elseif ($user->role_id == 2) {
            Auth::login($user);
            return redirect()->route('potrebitel.index');
        }
    }
}
