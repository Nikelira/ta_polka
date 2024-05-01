<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
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

            if (isset($cart[$products->id])) {
                $cart[$products->id] += $quantity;
            } else {
                $cart[$products->id] = $quantity;
            }

            session()->put('cart', $cart);

            $cartContents = session('cart', []);

            return response()->json([
                'message' => 'Блюдо добавлено в корзину',
                'cart' => $cartContents,
            ]);
        } else {
            return response()->json([
                'message' => 'Блюдо не найдено',
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

        return response()->json([
            'message' => 'Корзина очищена',
        ]);
    }

    

}
