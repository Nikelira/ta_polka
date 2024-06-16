<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($request->filled('surname')) {
            $validatedData = $request->validate([
                'surname' => 'required|string|max:50',
                'name' => 'required|string|max:50',
                'Partonymic' => 'required|string|max:50',
                'login' => 'required|string|max:50|unique:users,login,' . $user->id,
            ]);
            $user->update($validatedData);
        }

        if ($request->filled('login')) {
            $validatedData = $request->validate([
                'login' => 'required|string|max:50|unique:users,login,' . $user->id,
            ]);
            $user->update($validatedData);
        }


        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
        }

        return redirect()->back()->with('success', 'Профиль успешно обновлен.');
    }

    public function destroy()
    {
        $user = Auth::user();
        
        // Помечаем аккаунт пользователя как удаленный (например, изменяем статус на 2)
        $user->account_status_id = 2;
        $user->save();
        
        // Выйти из системы после удаления аккаунта
        Auth::logout();

        // Редирект на страницу, например, домашнюю
        return redirect()->route('home')->with('status', 'Ваш аккаунт успешно удален.');
    }
}
