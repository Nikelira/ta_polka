<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Session;

class ShopController extends Controller
{
    //каталог
    public function index(){
        $products = Product::where('product_status_id', 1)->where('count', '>', 0)->orderByDesc('id')->get();
        $cart = Session::get('cart', []);
        return view('products', compact('products', 'cart'));
    }


}
