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
            'surname.max'=> 'Максимальная длина фамилии - 50 символов',
            'name.max'=> 'Максимальная длина имени - 50 символов',
            'Partonymic.max'=> 'Максимальная длина отчества - 50 символов',
            'login.unique'  => 'Данный логин уже используется',
            'password.confirmed'  => 'Пароли должны совпадать',
            'agree.accepted'  => 'Необходимо подтвердить согласие на обработку данных',
        ];
        //валидация значений
        $request->validate([
            'surname' => 'required|string|max:50',
            'name' => 'required|string|max:50',
            'Partonymic' => 'required|string|max:50',
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
            'account_status_id'=>1,
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }
}
