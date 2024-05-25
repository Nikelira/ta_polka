<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;

class CabinetZakazController extends Controller
{
    //
    public function index(){
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with('compositions.product')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        return view('cabinet_zakaz', compact('orders'));
    }

    public function cancel(Request $request, $orderId)
    {
        // Найти заказ по ID
        $order = Order::find($orderId);

        // Проверить, что заказ существует и принадлежит текущему пользователю
        if (!$order || $order->user_id !== Auth::id()) {
            return redirect()->route('cabinet_zakaz.index')->with('error', 'Заказ не найден или вы не можете отказаться от этого заказа.');
        }

        // Проверить статус заказа
        if ($order->order_status_id == 1 || $order->order_status_id == 2) {
            // Вернуть товары в таблицу продуктов
            foreach ($order->compositions as $composition) {
                $product = Product::find($composition->product_id);

                if ($product) {
                    $previousQuantity = $product->count;
                    $product->count += $composition->count;

                    // Если количество товара было 0, изменяем статус на 1
                    if ($previousQuantity == 0) {
                        $product->product_status_id = 1;
                    }

                    $product->save();
                }
            }

            // Изменить статус заказа на "отменен"
            $order->order_status_id = 5;
            $order->save();

            return redirect()->route('cabinet_zakaz.index')->with('success', 'Заказ был успешно отменен.');
        }

        return redirect()->route('cabinet_zakaz.index')->with('error', 'Вы не можете отказаться от этого заказа.');
    }
}
