<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SupplierController;

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


Auth::routes();

Route::redirect('/', '/home', 302);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');



    // Admin
    Route::middleware(['admin'])->group(function () {
        // Accounts
        Route::put('/accounts/restore', [AccountController::class, 'restore'])->name('accounts.restore');
        Route::resource('accounts', AccountController::class);

        // Suppliers
        Route::put('/suppliers/restore', [SupplierController::class, 'restore'])->name('suppliers.restore');
        Route::resource('suppliers', SupplierController::class);
    });

});
