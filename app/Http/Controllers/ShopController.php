<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use Session;

class ShopController extends Controller
{
    //каталог
    public function index(Request $request)
    {
        $searchTerm = "";
        $productsQuery = Product::where('product_status_id', 1)
            ->where('count', '>', 0)
            ->orderByDesc('id');

        // Проверяем, был ли отправлен запрос на поиск
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $productsQuery->where('name', 'like', '%' . $searchTerm . '%');
        }

        $products = $productsQuery->get();
        $cart = Session::get('cart', []);
        $productCategories = ProductCategory::all();
        return view('products', compact('products', 'cart', 'productCategories', 'searchTerm'));
    }

    public function productsByCategory(Request $request)
    {
        $searchTerm = "";
        $categoryId = $request->input('category_id');
        $productsQuery = Product::where('product_status_id', 1)
            ->where('count', '>', 0)
            ->where('product_category_id', $categoryId)
            ->orderByDesc('id');

        $products = $productsQuery->get();
        $cart = Session::get('cart', []);
        $productCategories = ProductCategory::all();
        return view('products', compact('products', 'cart', 'productCategories', 'searchTerm'));
    }
}
