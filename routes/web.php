<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\MainPage;
use App\Http\Controllers\Auther;
use App\Http\Controllers\Registration;
use App\Http\Controllers\Postavshic;
use App\Http\Controllers\Potrebitel;
use App\Http\Controllers\Prodavec;
use App\Http\Controllers\Meneger;
use App\Http\Controllers\Administrator;
use App\Http\Controllers\Employes;
use App\Http\Middleware\Role;

Route::get('/', [MainPage::class, 'index'])->name('home');
Route::post('/add_to_cart', [MainPage::class, 'addToCart'])->name('add_to_cart');
Route::get('/clear-cart', [MainPage::class, 'clearCart'])->name('clearCart');
Route::get('/dish/{id}', [MainPage::class, 'getDishInfo'])->name('product_ids');

Route::get('/create-order', [MainPage::class, 'СreateOrder'])->name('main.createOrder');//Создание бронирования

Route::get('/products', [ShopController::class, 'index'])->name('products');
Route::get('/products/category', [ShopController::class, 'productsByCategory'])->name('products.category');

Route::get('/registration', [Registration::class,'index'])->middleware('guest')->name('reg.index');
Route::post('/registration', [Registration::class,'store'])->middleware('guest')->name('reg.store');

Route::get('/auth', [Auther::class,'index'])->middleware('guest')->name('login');
Route::post('/auth', [Auther::class,'store'])->middleware('guest')->name('auth.store');
Route::get('/logout', [Auther::class, 'logout'])->middleware('auth')->name('logout');

Route::group(['middleware' => Role::class . ':1'], function () {
    Route::get('/main_postavshik', [Postavshic::class, 'index'])->middleware('auth')->name('postavshik.index');
});

Route::group(['middleware' => Role::class . ':2'], function () {
    Route::get('/main_potrebitel', [Potrebitel::class, 'index'])->middleware('auth')->name('potrebitel.index');
});

Route::group(['middleware' => Role::class . ':3'], function () {
    Route::get('/main_prodavec', [Prodavec::class, 'index'])->middleware('auth')->name('prodavec.index');
});

Route::group(['middleware' => Role::class . ':4'], function () {
    Route::get('/main_meneger', [Meneger::class, 'index'])->middleware('auth')->name('meneger.index');
});

Route::group(['middleware' => Role::class . ':5'], function () {
    Route::get('/main_administrator', [Administrator::class, 'index'])->middleware('auth')->name('administrator.index');
    Route::get('/main_administrator/employes', [Employes::class, 'index'])->middleware('auth')->name('employes.index');
    Route::post('/main_administrator/employes/add-employee', [Employes::class, 'store'])->middleware('auth')->name('employes.add');
    Route::post('/main_administrator/employes/{id}/deactivate', [Employes::class, 'deactivate'])->middleware('auth')->name('employees.deactivate');
    Route::post('/main_administrator/employes/{id}/activate', [Employes::class, 'activate'])->middleware('auth')->name('employees.activate');
});
