<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterController;


Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Routes (Protected)
    Route::middleware(['auth'])->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/add-to-cart', [CheckoutController::class, 'addToCart'])->name('checkout.add');
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
    Route::patch('/update-cart', [CheckoutController::class, 'updateCart'])->name('checkout.update');
    Route::delete('/remove-from-cart', [CheckoutController::class, 'removeFromCart'])->name('checkout.remove');
    Route::get('/my-orders', [CheckoutController::class, 'history'])->name('checkout.history');
});

// Protect these routes with BOTH 'auth' (must be logged in) AND 'admin' (must be admin)
    Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/restock/{id}', [AdminController::class, 'restock'])->name('admin.restock');
    Route::get('/admin/sales', [AdminController::class, 'salesReport'])->name('admin.sales');
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/delete/{id}', [AdminController::class, 'destroy'])->name('admin.delete');
});
