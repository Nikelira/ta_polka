<?php

namespace App\Http\Controllers;

use App\Models\RentalApplication;
use App\Models\RentalContract;
use Carbon\Carbon; 
use Illuminate\Http\Request;

class ContractPostavshik extends Controller
{
    //
    public function index(Request $request){
        $user = auth()->user();
    
        // Текущая дата
        $today = Carbon::today();

        // Получение категории договоров (по умолчанию 'active')
        $category = $request->input('category', 'active');

        // Получение заявок, принадлежащих текущему пользователю
        $userApplicationIds = RentalApplication::where('user_id', $user->id)->pluck('id');

        // Получение договоров для текущего пользователя в зависимости от категории
        if ($category == 'active') {
            // Активные договоры (сегодня или в будущем)
            $contracts = RentalContract::whereIn('rental_application_id', $userApplicationIds)
                                        ->where('date_end', '>=', $today)
                                        ->get();
        } else {
            // Завершенные договоры (в прошлом)
            $contracts = RentalContract::whereIn('rental_application_id', $userApplicationIds)
                                        ->where('date_end', '<', $today)
                                        ->get();
        }
        // Возврат представления с договорами и категорией
        return view('postavshik.contracts', compact('contracts', 'category'));
    }
}
