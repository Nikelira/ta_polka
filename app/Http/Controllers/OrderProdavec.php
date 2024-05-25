<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderComposition;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderProdavec extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $category = $request->input('category', 'active');

        $query = Order::with('compositions.product');

        switch ($category) {
            case 'to_issue':
                $query->where('order_status_id', 2); // Заказы на выдачу
                break;
            case 'issued':
                $query->where('order_status_id', 4); // Выданные заказы
                break;
            case 'cancelled':
                $query->where('order_status_id', 3); // Отмененные заказы
                break;
            case 'all':
                // Загружаем все заказы
                break;
            case 'active':
            default:
                $query->where('order_status_id', 1); // Активные заказы
                break;
        }

        $orders = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        return view('prodavec.orders', compact('orders', 'category'));
    }

    public function confirm(Request $request, $orderId)
    {
        // Найти заказ по ID
        $order = Order::find($orderId);
        // Проверить, что заказ существует
        if (!$order) {
            return redirect()->route('orders_prodavec.index')->with('error', 'Заказ не найден или вы не можете одобрить этот заказ.');
        }

        // Проверить статус заказа
        if ($order->order_status_id == 1) {
            // Изменить статус заказа на "одобрен"
            $order->order_status_id = 2;
            $order->save();

            return redirect()->route('orders_prodavec.index')->with('success', 'Заказ был успешно одобрен.');
        }

        return redirect()->route('orders_prodavec.index')->with('error', 'Вы не можете одобрить этот заказ.');
    }

    public function cancel(Request $request, $orderId)
    {
        // Найти заказ по ID
        $order = Order::find($orderId);

        // Проверить, что заказ существует и принадлежит текущему пользователю
        if (!$order) {
            return redirect()->route('orders_prodavec.index')->with('error', 'Заказ не найден или вы не можете отказаться от этого заказа.');
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
            $order->order_status_id = 4; // Отмененные заказы
            $order->save();

            return redirect()->route('orders_prodavec.index')->with('success', 'Заказ был успешно отменен.');
        }

        return redirect()->route('orders_prodavec.index')->with('error', 'Вы не можете отказаться от этого заказа.');
    }

    public function finish(Request $request, $orderId)
    {
        // Найти заказ по ID
        $order = Order::find($orderId);
        // Проверить, что заказ существует
        if (!$order) {
            return redirect()->route('orders_prodavec.index')->with('error', 'Заказ не найден или вы не можете выдать этот заказ.');
        }

        // Проверить статус заказа
        if ($order->order_status_id == 2) {
            // Изменить статус заказа на "одобрен"
            $order->order_status_id = 4;
            $order->save();

            return redirect()->route('orders_prodavec.index')->with('success', 'Заказ был завершен.');
        }

        return redirect()->route('orders_prodavec.index')->with('error', 'Вы не можете выдать этот заказ.');
    }
}
