<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderComposition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Orders extends Controller
{
    public function index(Request $request)
    {
        // Получение данных корзины из сессии
        $cart = $request->session()->get('cart', []);
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get();
        $payments = Payment::all();

        return view('order', compact('products', 'payments', 'cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);

        $cart = $request->session()->get('cart', []);
        
        // Проверка наличия товаров в корзине
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Ваша корзина пуста.');
        }
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Получение текущего пользователя
        $user = Auth::user();

        // Проверка роли пользователя
        if ($user->role_id != 1 && $user->role_id != 2) {
            return redirect()->route('home')->with('error', 'У вас нет доступа к этой странице.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        $total = 0;
        foreach ($products as $product) {
            $total += $product->cost * $cart[$product->id];
        }

        // Начало транзакции
        DB::beginTransaction();

        try {
            $order = Order::create([
                'date' => Carbon::now(),
                'user_id' => Auth::id(),
                'order_status_id' => 1, // предполагаем, что статус "новый" имеет id 1
                'payment_id' => $request->input('payment_id'),
                'summa' => $total,
            ]);

            foreach ($products as $product) {
                $quantity = $cart[$product->id];
                
                // Проверка наличия достаточного количества товара на складе
                if ($product->count < $quantity) {
                    throw new \Exception("Недостаточно товара {$product->name} на складе.");
                }
                
                // Уменьшение количества товара на складе
                $product->count -= $quantity;
                
                // Проверка, нужно ли обновить статус товара
                if ($product->count == 0) {
                    $product->product_status_id = 2; // предполагаем, что статус "2" означает "нет в наличии"
                }

                $product->save();

                OrderComposition::create([
                    'product_id' => $product->id,
                    'order_id' => $order->id,
                    'count' => $quantity,
                ]);
            }

            // Очистка корзины
            $request->session()->forget('cart');

            // Завершение транзакции
            DB::commit();

            return redirect()->route('home')->with('success', 'Ваш заказ был успешно оформлен.');
        } catch (\Exception $e) {
            // Откат транзакции в случае ошибки
            DB::rollBack();
            
            return redirect()->route('home')->with('error', $e->getMessage());
        }
    }

    public function clearCart(Request $request)
    {
        $request->session()->forget('cart');
        return redirect()->route('checkout.index')->with('success', 'Корзина была успешно очищена.');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $product = Product::find($id);

        if ($product) {
            if (isset($cart[$id])) {
                if ($request->action == 'increase' && $cart[$id] < $product->count) {
                    $cart[$id]++;
                } elseif ($request->action == 'decrease') {
                    if ($cart[$id] > 1) {
                        $cart[$id]--;
                    } else {
                        unset($cart[$id]);
                    }
                }
            } else {
                if ($request->action == 'increase' && $product->count > 0) {
                    $cart[$id] = 1;
                }
            }

            session()->put('cart', $cart);

            $cost = isset($cart[$id]) ? $cart[$id] * $product->cost : 0;
            $total = 0;
            foreach ($cart as $productId => $quantity) {
                $total += $quantity * Product::find($productId)->cost;
            }

            return response()->json([
                'success' => true,
                'cart' => $cart,
                'cost' => $cost,
                'total' => $total,
                'available' => $product->count - ($cart[$id] ?? 0)
            ]);
        }

        return response()->json(['success' => false], 400);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        $total = 0;
        foreach ($cart as $productId => $quantity) {
            $total += $quantity * Product::find($productId)->cost;
        }

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'total' => $total
        ]);
    }
}
