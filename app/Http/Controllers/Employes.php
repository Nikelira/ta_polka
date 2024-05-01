<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;


class Employes extends Controller
{
    public function index(){
        $users = User::with('role', 'accountStatus')
        ->whereHas('role', function ($query) {
            $query->whereIn('id', [3, 4, 5]);
        })
        ->orderBy('surname')
        ->orderBy('name')
        ->orderBy('Partonymic')
        ->get();
        return view('administrator.employes', compact('users'));
    }
    public function store(Request $request){
        //сообщение об ошибках
        $messages = [
            'login.required'  => 'Выберите другой логин',
            'password.required'  => 'Пароль должен совпадать',
        ];
        //валидация значений
        $request->validate([
            'login'=> ['required', 'string', 'alpha_dash', 'unique:users'],
            'password'=> 'required|confirmed',
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
        return redirect('/main_administrator/employes');
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->account_status_id = 2; // Изменяем статус сотрудника на 2
        $user->save();
        return response()->json(['message' => 'Сотрудник успешно удален'], 200);
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->account_status_id = 1; // Изменяем статус сотрудника на 1
        $user->save();
        return response()->json(['message' => 'Сотрудник успешно восстановлен'], 200);
    }
   /* public function edit($id){
        $user = User::findOrFail($id);
        return view('administrator.employes_edit', compact('user'));
    }*/
}
