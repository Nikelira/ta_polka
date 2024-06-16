<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Models\Shelf;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Session;
use App\Models\RentalApplication;
use App\Models\CompositionRentalApplication;
use App\Models\Product;

class Postavshic extends Controller
{
    //загрузка страницы
    public function index(Request $request){
        $userId = Auth::id();
        // Получаем статус из запроса, по умолчанию - 2 (Активные заявки)
        $status = $request->input('status', 2);

        // Определяем базовый запрос
        $query = RentalApplication::where('user_id', $userId);

        // Фильтруем заявки по статусу
        if (in_array($status, [2, 3, 4, 5])) {
            $query->where('application_status_id', $status);
        }

        // Сортируем по дате и идентификатору
        $applications = $query->orderBy('date_application', 'desc')
                            ->orderBy('id', 'desc')
                            ->get();

        return view('postavshik.application', compact('applications', 'status'));
    }

    public function show($id)
    {
        // Получаем идентификатор текущего пользователя
        $userId = Auth::id();

        // Загружаем заявку по ID и проверяем, что она принадлежит текущему пользователю
        $application = RentalApplication::where('id', $id)
            ->where('user_id', $userId)
            ->with(['compositions.shelf', 'products', 'products.status', 'applicationStatus', 'user'])
            ->firstOrFail();

        return view('postavshik.detail_application', compact('application'));
    }

    public function delete(Request $request)
    {
        $applicationId = $request->input('application_id');
        // Update the status of the rental application
        $application = RentalApplication::findOrFail($applicationId);
        $application->application_status_id = 5;
        $application->save();
        // Get all shelf IDs related to this rental application
        $shelfIds = CompositionRentalApplication::where('rental_application_id', $applicationId)->pluck('shelf_id');
        // Update the status of the shelves
        Shelf::whereIn('id', $shelfIds)->update(['shelf_status_id' => 1]);
        // Update the status of the products related to this rental application
        Product::where('rental_application_id', $applicationId)->update(['product_status_id' => 6]);
        return response()->json(['success' => true]);
    }
}
