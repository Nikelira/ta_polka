<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductStatus;
use App\Models\RentalApplication;

class Prodavec extends Controller
{
    //загрузка страницы
    public function index(){
        return view('prodavec.mains');
    }

    public function index1()
    {
        $products = Product::where('product_status_id', 1)
        ->orWhere('product_status_id', 2)
        ->get();

        return view('prodavec.product', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $statuses = ProductStatus::all();
        $applications = RentalApplication::all();
        return view('prodavec.products_create', compact('categories', 'statuses', 'applications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'count' => 'required|integer',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:product_categories,id',
            'status_id' => 'required|exists:product_statuses,id',
            'rental_application_id' => 'required|exists:rental_applications,id', // добавляем проверку
        ]);

        // Сохранение нового товара
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->cost = $request->cost;
        $product->count = $request->count;
        $product->product_category_id = $request->category_id;
        $product->product_status_id = $request->status_id;
        $product->rental_application_id = $request->rental_application_id;

        // Загрузка изображения
        if ($request->hasFile('photo')) {
            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('images'), $imageName);
            $product->photo_path = $imageName;
        }

        $product->save();

        return redirect()->route('products.index1')->with('success', 'Товар успешно создан!');
    }
    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        $statuses = ProductStatus::all();
        $products = Product::all();
        return view('prodavec.products_edit', compact('product', 'categories', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'count' => 'required|integer',
            'category_id' => 'required|exists:product_categories,id',
            'status_id' => 'required|exists:product_statuses,id',
            'new_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->cost = $request->cost;
        $product->count = $request->count;
        $product->product_category_id = $request->category_id;
        $product->product_status_id = $request->status_id;

        if ($request->hasFile('new_photo')) {
            // Загрузка нового изображения
            $imagePath = time().'.'.$request->new_photo->extension();
            $request->new_photo->move(public_path('images'), $imagePath);
            $product->photo_path = $imagePath;
        }

        $product->save();

        return redirect()->route('products.index1')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Удаление изображения
        if ($product->photo_path) {
            $image_path = public_path('images') . '/' . $product->photo_path;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $product->delete();

        return redirect()->route('products.index1')->with('success', 'Товар успешно удален!');
    }

}
