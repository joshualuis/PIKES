<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified', 'role:admin,customer'])->name('dashboard');


Route::middleware('auth', 'role:admin, customer, employee')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});

Route::get('products', [LandingController::class, 'products'])->name('customer.products');

Route::middleware('auth', 'role:customer')->group(function () {

    Route::get('add-to-cart/{product}', [CartController::class, 'addToCart'])->name('add_to_cart');
    Route::get('remove-from-cart/{product}', [CartController::class, 'removeFromCart'])->name('remove_from_cart');
    Route::get('add/{product}', [CartController::class, 'add'])->name('add');
    Route::get('remove/{product}', [CartController::class, 'remove'])->name('remove');
    Route::get('mycart', [LandingController::class, 'checkout'])->name('checkout');

    Route::get('confirmation/{order}', [LandingController::class, 'confirmation'])->name('confirmation');
    Route::post('orders', [OrderController::class, 'store'])->name('customer.order.store');

    Route::get('customer/profile/{customer}', [CustomerController::class, 'profile'])->name('customer.profile');
});


Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/orders/forward/{order}', [OrderController::class, 'forward'])->name('admin.orders.forward');
    Route::get('/admin/orders/backward/{order}', [OrderController::class, 'backward'])->name('admin.orders.backward');
    
    Route::resource('admin/orders', OrderController::class)->only([
        'destroy', 'show', 'store', 'update', 'edit', 'index', 'create',]);

    Route::resource('admin/products', ProductController::class)->only([
        'destroy', 'show', 'store', 'update', 'edit', 'index', 'create',]);

    Route::resource('admin/notifications', NotificationController::class)->only([
        'destroy', 'show', 'store', 'update', 'edit', 'index', 'create',]);

    Route::resource('admin/announcements', AnnouncementController::class)->only([
        'destroy', 'show', 'store', 'update', 'edit', 'index', 'create',]);

    Route::resource('admin/packages', PackageController::class)->only([
        'destroy', 'show', 'store', 'update', 'edit', 'index', 'create',]);
    
});



require __DIR__.'/auth.php';
