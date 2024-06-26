<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CabinetZakazController;
use App\Http\Controllers\MainPage;
use App\Http\Controllers\Auther;
use App\Http\Controllers\Registration;
use App\Http\Controllers\Postavshic;
use App\Http\Controllers\Potrebitel;
use App\Http\Controllers\Prodavec;
use App\Http\Controllers\Meneger;
use App\Http\Controllers\Administrator;
use App\Http\Controllers\Employes;
use App\Http\Controllers\Orders;
use App\Http\Controllers\AboutShop;
use App\Http\Controllers\OrderProdavec;
use App\Http\Controllers\ApplicationRental;
use App\Http\Controllers\ContractMeneger;
use App\Http\Controllers\ContractPostavshik;
use App\Http\Middleware\Role;

Route::get('/', [MainPage::class, 'index'])->name('home');
Route::post('/add_to_cart', [MainPage::class, 'addToCart'])->name('add_to_cart');
Route::get('/clear-cart', [MainPage::class, 'clearCart'])->name('clearCart');
Route::get('/dish/{id}', [MainPage::class, 'getDishInfo'])->name('product_ids');
Route::get('/get_cart', [MainPage::class, 'getCart'])->name('get_cart');
Route::get('/check_cart_items', [MainPage::class, 'checkCartItems'])->name('check_cart_items');

Route::get('/create-order', [MainPage::class, 'СreateOrder'])->name('main.createOrder');//Создание бронирования

Route::get('/products', [ShopController::class, 'index'])->name('products');
Route::get('/products/category', [ShopController::class, 'productsByCategory'])->name('products.category');

Route::get('/shelf', [ShelfController::class, 'index'])->name('shelf');
Route::get('/about_shop', [AboutShop::class, 'index'])->name('about_shop');

Route::get('/registration', [Registration::class,'index'])->middleware('guest')->name('reg.index');
Route::post('/registration', [Registration::class,'store'])->middleware('guest')->name('reg.store');

Route::get('/auth', [Auther::class,'index'])->middleware('guest')->name('login');
Route::post('/auth', [Auther::class,'store'])->middleware('guest')->name('auth.store');
Route::get('/logout', [Auther::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth')->name('profile.index');
Route::put('/profile/update', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');
Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->middleware('auth')->name('profile.delete');

Route::get('/checkout', [Orders::class, 'index'])->name('checkout.index');
Route::post('/checkout', [Orders::class, 'store'])->name('checkout.store');
Route::delete('/checkout/clear', [Orders::class, 'clearCart'])->name('checkout.clear');
Route::patch('/checkout/update/{id}', [Orders::class, 'update'])->name('checkout.update');
Route::delete('/checkout/remove/{id}', [Orders::class, 'remove'])->name('checkout.remove');

Route::group(['middleware' => ['auth', Role::class . ':1']], function () {
    Route::get('/main_potrebitel', [Potrebitel::class, 'index'])->name('potrebitel.index');
});

Route::group(['middleware' => ['auth', Role::class . ':2']], function () {
    Route::post('/shelf/select', [ShelfController::class, 'selectShelf'])->name('cooperation.select');
    Route::post('/shelf/deselect', [ShelfController::class, 'deselectShelf'])->name('cooperation.deselect');

    Route::get('/cooperation', [ApplicationRental::class, 'index'])->name('cooperation');
    Route::post('/cooperation/deselect', [ApplicationRental::class, 'deselect'])->name('cooperation.deselects');
    Route::post('/cooperation/addProduct', [ApplicationRental::class, 'addProduct'])->name('cooperation.addProduct');
    Route::put('/cooperation/{id}', [ApplicationRental::class, 'updateProduct'])->name('cooperation.update');
    Route::delete('/cooperation/{id}', [ApplicationRental::class, 'deleteProduct'])->name('cooperation.deleteProduct');
    Route::post('/cooperation/submit', [ApplicationRental::class, 'submitRentalRequest'])->name('cooperation.submit');
    
    Route::get('/application', [Postavshic::class, 'index'])->name('postavshik.index');
    Route::get('/application/{id}', [Postavshic::class, 'show'])->name('postavshik.show');
    Route::post('/application/delete', [Postavshic::class, 'delete'])->name('application.delete');

    Route::get('/postavshik/contracts', [ContractPostavshik::class, 'index'])->name('contracts_postavshik.index');

    
});

Route::group(['middleware' => ['auth',  Role::class . ':1,2']], function () {
    Route::get('/orders', [CabinetZakazController::class, 'index'])->name('cabinet_zakaz.index');
    Route::post('/orders/{orderId}/cancel', [CabinetZakazController::class, 'cancel'])->name('cabinet_zakaz.cancel');
});

Route::group(['middleware' => Role::class . ':3'], function () {
    Route::get('/main_prodavec', [Prodavec::class, 'index'])->middleware('auth')->name('prodavec.index');
    Route::get('/prodavec/products', [Prodavec::class, 'index1'])->middleware('auth')->name('products.index1');
    Route::get('/prodavec//products/create', [Prodavec::class, 'create'])->name('products.create');
    Route::post('/prodavec//products', [Prodavec::class, 'store'])->name('products.store');
    Route::get('/prodavec/products/{product}/edit', [Prodavec::class,'edit'])->middleware('auth')->name('products.edit');
    Route::put('/prodavec/products/{product}', [Prodavec::class,'update'])->middleware('auth')->name('products.update');
    Route::delete('/prodavec/products/{id}', [Prodavec::class, 'destroy'])->name('products.destroy');

    Route::get('/prodavec/orders',[OrderProdavec::class,'index'])->name('orders_prodavec.index');
    Route::post('/prodavec/orders/{orderId}/confirm', [OrderProdavec::class, 'confirm'])->name('orders_prodavec.confirm');
    Route::post('/prodavec/orders/{orderId}/cancel', [OrderProdavec::class, 'cancel'])->name('orders_prodavec.cancel');
    Route::post('/prodavec/orders/{orderId}/finish', [OrderProdavec::class, 'finish'])->name('orders_prodavec.finish');


});

Route::group(['middleware' => Role::class . ':4'], function () {
    Route::get('/manager/applications', [Meneger::class, 'index'])->name('application.index');
    Route::get('/manager/applications/{id}', [Meneger::class, 'show'])->name('application.show');
    Route::post('/manager/applications/{id}/approve', [Meneger::class, 'approve'])->name('manager.rental-applications.approve');
    Route::post('/manager/applications/{id}/reject', [Meneger::class, 'reject'])->name('manager.rental-applications.reject');

    Route::get('/manager/contract', [ContractMeneger::class,'index'])->name('contracts.index');
    Route::get('/manager/contracts/create', [ContractMeneger::class, 'create'])->name('contracts.create');
    Route::get('/manager/contracts/application/{id}', [ContractMeneger::class, 'getApplicationData'])->name('contracts.applicationData');
    // Маршрут для сохранения нового договора
    Route::post('/manager/contracts', [ContractMeneger::class, 'store'])->name('contracts.store');
    // Маршруты для просмотра и редактирования существующего договора
    Route::get('/manager/contracts/{id}', [ContractMeneger::class, 'show'])->name('contracts.show');
    Route::get('/manager/contracts/{id}/edit', [ContractMeneger::class, 'edit'])->name('contracts.edit');
    Route::put('/manager/contracts/{id}', [ContractMeneger::class, 'update'])->name('contracts.update');
    Route::get('/manager/contracts/download/{id}', [ContractMeneger::class, 'download'])->name('contracts.download');

});

Route::group(['middleware' => Role::class . ':5'], function () {
    Route::get('/main_administrator', [Administrator::class, 'index'])->middleware('auth')->name('administrator.index');
    Route::get('/main_administrator/employes', [Employes::class, 'index'])->middleware('auth')->name('employes.index');
    Route::post('/main_administrator/employes/add-employee', [Employes::class, 'store'])->middleware('auth')->name('employes.add');
    Route::post('/main_administrator/employes/{id}/deactivate', [Employes::class, 'deactivate'])->middleware('auth')->name('employees.deactivate');
    Route::post('/main_administrator/employes/{id}/activate', [Employes::class, 'activate'])->middleware('auth')->name('employees.activate');
});
