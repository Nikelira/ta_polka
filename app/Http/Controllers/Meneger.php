<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalApplication;
use App\Models\Shelf;
use App\Models\CompositionRentalApplication;
use App\Models\Product;

class Meneger extends Controller
{
    //загрузка страницы
    public function index(Request $request)
    {
        $status = $request->input('status', 2); // Default to "All Applications"
        // Базовый запрос без выполнения
        $query = RentalApplication::query();

        // Фильтрация по статусу
        if ($status == 2) {
            $query->where('application_status_id', 2);
        } elseif ($status == 3) {
            $query->where('application_status_id', 3);
        }

        // Добавление сортировки
        $applications = $query->orderBy('created_at', 'desc')  // Сначала по дате (от свежей к старой)
                            ->orderBy('id', 'desc')          // Затем по номеру заявки (от большего к меньшему)
                            ->get();

        return view('meneger.application', compact('applications', 'status'));
    }

    public function show($id)
    {
        $application = RentalApplication::with(['compositions.shelf', 'products'])->findOrFail($id);
        // Assuming you want to maintain the status of the list from which this application was accessed
        $status = request()->query('status', 2);
        return view('meneger.detail_application', compact('application', 'status'));
    }

    public function approve(Request $request, $id)
    {
        $application = RentalApplication::findOrFail($id);

        // Ensure all products have a status
        foreach ($request->products as $productData) {
            $product = Product::findOrFail($productData['id']);
            $product->product_status_id = $productData['status'];
            $product->message = $productData['status'] == 5 ? $productData['message'] : null;
            $product->save();
        }

        // Change application status to approved
        $application->application_status_id = 3; // Approved status
        $application->save();

        return response()->json(['success' => true]);
    }
    public function reject($id, Request $request)
    {
    
        // Валидация запроса
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);
        // Найдите заявку по ID
        $application = RentalApplication::findOrFail($id);
        // Причина отказа всей заявки
        $rejectionReason = $request->input('reason');
        // Обновите статус всех товаров на "Отказано"
        foreach ($application->products as $product) {
            if ($product->product_status_id != 5 || empty($product->message)) {
                // Если товар не отклонен или причина отказа не заполнена, обновляем их
                $product->product_status_id = 5; // Статус "Отказано"
                $product->message = $rejectionReason;
            }
            $product->save();
        }
        // Найдите все полки, связанные с заявкой, и обновите их статус на 1
        $shelfIds = CompositionRentalApplication::where('rental_application_id', $id)->pluck('shelf_id');
        Shelf::whereIn('id', $shelfIds)->update(['shelf_status_id' => 1]);
        // Обновите статус заявки на 4
        $application->application_status_id = 4; // Статус заявки "Отклонено"
        $application->message = $request->input('reason'); // Добавьте причину отказа в заявку, если это необходимо
        $application->save();
        return response()->json(['success' => true]);
    }
}
