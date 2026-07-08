<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashierPosController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\StaffAccountController;

Route::get('/index.php/{path?}', function (?string $path = null) {
    return redirect('/'.ltrim($path ?? '', '/'), 301);
})->where('path', '.*');

Route::view('/', 'regcustome')->name('home');
Route::view('/home', 'home')->name('customer.home');
Route::view('/track-order', 'track-order')->name('track.order');
Route::redirect('/customer/register', '/', 301)->name('customer.register');
Route::post('/customer/register', [CustomerAccountController::class, 'register'])->name('customer.register.submit');
Route::view('/customer/menu', 'customermenu')->name('customer.menu');
Route::view('/admin/login', 'admin-login')->name('admin.login');
Route::view('/cashier/login', 'cashier-login')->name('cashier.login');
Route::post('/admin/login', [StaffAccountController::class, 'loginAdmin'])->name('admin.login.submit');
Route::post('/cashier/login', [StaffAccountController::class, 'loginCashier'])->name('cashier.login.submit');
Route::redirect('/admin', '/admin/dashboard')->name('admin');
Route::view('/admin/dashboard', 'admin', ['view' => 'dashboard'])->name('admin.dashboard');
Route::view('/admin/orders', 'admin', ['view' => 'allorders'])->name('admin.orders');
Route::view('/admin/menu', 'admin', ['view' => 'menu'])->name('admin.menu');
Route::view('/admin/inventory', 'admin', ['view' => 'inventory'])->name('admin.inventory');
Route::view('/admin/staff', 'admin', ['view' => 'staff'])->name('admin.staff');
Route::view('/cashier-pos', 'cashier-pos')->name('cashier.pos');
Route::get('/cashier-pos/data', [CashierPosController::class, 'data'])->name('cashier.pos.data');
Route::post('/cashier-pos/data', [CashierPosController::class, 'save'])->name('cashier.pos.save');
Route::post('/cashier-pos/menu-photo', [CashierPosController::class, 'storeMenuPhoto'])->name('cashier.pos.menu-photo');
Route::post('/customer/menu/order', [CashierPosController::class, 'storeCustomerOrder'])->name('customer.menu.order');
Route::get('/admin/staff/accounts', [StaffAccountController::class, 'index'])->name('admin.staff.accounts.index');
Route::post('/admin/staff/accounts', [StaffAccountController::class, 'store'])->name('admin.staff.accounts.store');
Route::put('/admin/staff/accounts/{id}', [StaffAccountController::class, 'update'])->name('admin.staff.accounts.update');
Route::patch('/admin/staff/accounts/{id}/status', [StaffAccountController::class, 'toggleStatus'])->name('admin.staff.accounts.status');
Route::delete('/admin/staff/accounts/{id}', [StaffAccountController::class, 'destroy'])->name('admin.staff.accounts.destroy');

Route::redirect('/cashier', '/cashier/login', 301)->name('cashier');
Route::redirect('/kitchen', '/', 301)->name('kitchen');
