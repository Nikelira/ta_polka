<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shelf;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\RentalApplication;
use App\Models\CompositionRentalApplication;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ApplicationRental extends Controller
{
    public function index(Request $request)
    {
        // Получение выбранных полок по их уникальным идентификаторам
        $selectedShelfIds = session('selected_shelves', []);
        $selectedShelvesObjects = Shelf::whereIn('id', $selectedShelfIds)->get();

        // Получение категорий товаров
        $productCategories = ProductCategory::all();
        return view('postavshik.cooperation', compact('selectedShelvesObjects', 'productCategories'));
    }

    public function deselect(Request $request)
    {
        $shelfId = $request->input('shelf_id');
        $selectedShelves = session('selected_shelves', []);
        
        // Удаление полки из сессии
        if (($key = array_search($shelfId, $selectedShelves)) !== false) {
            unset($selectedShelves[$key]);
        }

        session(['selected_shelves' => array_values($selectedShelves)]);

        return response()->json(['success' => true, 'message' => 'Полка удалена из сессии']);
    }

    public function addProduct(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'product_category_id' => 'required|exists:product_categories,id',
            'description' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cost' => 'required|numeric',
            'count' => 'required|integer'
        ]);
    
        if ($request->hasFile('photo')) {
            $imageName = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('images'), $imageName);
        } 
        else {
            return response()->json(['success' => false, 'message' => 'Не удалось загрузить изображение']);
        }

        $product = [
            'id' => time(), 
            'name' => $validatedData['name'],
            'product_category_id' => $validatedData['product_category_id'],
            'description' => $validatedData['description'],
            'photo' => $imageName,
            'cost' => $validatedData['cost'],
            'count' => $validatedData['count']
        ];
    
        $products = session('products', []);
        $products[] = $product;
    
        session(['products' => $products]);
    
        return response()->json(['success' => true, 'message' => 'Товар добавлен']);
    }

    public function updateProduct(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'product_category_id' => 'required|exists:product_categories,id',
            'description' => 'required|string',
            'cost' => 'required|numeric',
            'count' => 'required|integer'
        ]);

        $products = session('products', []);

        foreach ($products as &$product) {
            if ($product['id'] == $id) {
                $product['name'] = $validatedData['name'];
                $product['product_category_id'] = $validatedData['product_category_id'];
                $product['description'] = $validatedData['description'];
                $product['cost'] = $validatedData['cost'];
                $product['count'] = $validatedData['count'];

                session(['products' => $products]);

                return response()->json(['success' => true, 'message' => 'Товар обновлен']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Товар с указанным ID не найден']);
    }

    public function deleteProduct(Request $request, $id)
    {
        try {
            $products = session('products', []);

            // Найдите продукт по ID и удалите его
            foreach ($products as $key => $product) {
                if ($product['id'] == $id) {
                    // Удалите картинку из директории
                    $imagePath = public_path('images/' . $product['photo']);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }

                    // Удалите продукт из массива
                    unset($products[$key]);
                    session(['products' => array_values($products)]);

                    return response()->json(['success' => true, 'message' => 'Товар удален']);
                }
            }

            return response()->json(['success' => false, 'message' => 'Товар не найден'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Ошибка удаления товара: ' . $e->getMessage()], 500);
        }
    }

    public function submitRentalRequest(Request $request)
    {
        DB::beginTransaction();
        try {
            // Создаем заявку на аренду
            $rentalApplication = new RentalApplication();
            $rentalApplication->date_application = now();
            $rentalApplication->user_id = Auth::id();
            $rentalApplication->application_status_id = 2; // Статус "В ожидании"
            $rentalApplication->save();
            
            // Добавляем полки в заявку и обновляем их статус
            $selectedShelves = session('selected_shelves', []);
            foreach ($selectedShelves as $shelfId) {
                $compositionRentalApplication = new CompositionRentalApplication();
                $compositionRentalApplication->shelf_id = $shelfId;
                $compositionRentalApplication->rental_application_id = $rentalApplication->id;
                $compositionRentalApplication->save();
    
                // Обновляем статус полки
                Shelf::where('id', $shelfId)->update(['shelf_status_id' => 2]);
            }
            
            // Добавляем товары в заявку
            $products = session('products', []);
            foreach ($products as $productData) {
                $product = new Product();
                $product->name = $productData['name'];
                $product->product_status_id = 3; // Статус "На рассмотрении"
                $product->rental_application_id = $rentalApplication->id;
                $product->product_category_id = $productData['product_category_id'];
                $product->description = $productData['description'];
                $product->photo_path = $productData['photo'];
                $product->cost = $productData['cost'];
                $product->count = $productData['count'];
                $product->save();
            }
            DB::commit();
            // Очищаем сессии после успешного сохранения
            $request->session()->forget('selected_shelves');
            $request->session()->forget('products');
            return response()->json(['success' => true, 'message' => 'Заявка успешно отправлена.']);
        } 
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Ошибка при отправке заявки: ' . $e->getMessage()]);
        }
    }

}
