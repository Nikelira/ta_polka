<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategories;
use App\Models\Booking;
use App\Models\BookingComposition;
use Illuminate\Support\Facades\Auth;

use Session;

class MainPage extends Controller
{
    //
    public function index(){
        $products = Product::where('product_status_id', 1)->where('count', '>', 0)->orderByDesc('id')->take(4)->get();
        $cart = Session::get('cart', []);
        return view('main', compact('products', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $products = Product::find($request->input('item_id'));

        if ($products) {
            $quantity = $request->input('quantity');

            $cart = session('cart', []);

            // Проверить, существует ли корзина в сеансе
            if (!isset($cart)) {
                $cart = [];
            }

            $cart[$products->id] = $quantity;

            session()->put('cart', $cart);

            $cartContents = session('cart', []);

            return response()->json([
                'message' => 'Товар добавлен в корзину',
                'cart' => $cartContents,
            ]);
        } else {
            return response()->json([
                'message' => 'Товар не найден',
            ], 404);
        }
    }

    public function getDishInfo($id) {
        $products = Product::find($id);
        return response()->json($products);
    }

    public function clearCart()
    {
        session()->forget('cart');
    return response()->json(['status' => 'Корзина очищена']);
    }

    public function getCart() {
        $cart = session('cart', []);
        return response()->json(['cart' => $cart]);
    }

    public function СreateOrder(Request $request){
        //Проверка пользователя на авторизацию
        if (!Auth::check()) {
            // Перенаправить на страницу входа, если пользователь не авторизован
            return redirect()->route('auth.index');
        }

        // Получить данные корзины
        $cart = session('cart');

        $total = 0;
        foreach ($cart as $item => $quantity) {
            $product = Product::find($item);
            $total += $product->cost * $quantity;
        }

        // Создать новый заказ
        $booking = new Booking;
        $booking->summa = $total;
        $booking->users_id = Auth::user()->id;
        $booking->booking_status_id = 1;

        // Сохранить заказ
        $booking->save();

        // Добавить товары из корзины к заказу
        foreach ($cart as $id => $quantity) {
            $bookingItem = new BookingComposition;
            $bookingItem->product_id = $id;
            $bookingItem->count = $quantity;
            $bookingItem->booking_id = $booking->id;
            $bookingItem->save();
        }

        // Удалить корзину из сессии
        session()->forget('cart');

        $products = Product::all();
        $categories = ProductCategories::with('productCategories')->get();
        $cart = Session::get('cart', []);

        return view('main',  compact('products', 'categories', 'cart'));
    }

    public function checkCartItems(Request $request)
    {
        $cart = session('cart', []);
        return response()->json(['cart' => $cart]);
    }

}
