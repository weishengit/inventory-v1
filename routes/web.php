<?php

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReleaseOrderController;
use App\Http\Controllers\PurchaseOrderController;

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
    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // Incoming
    Route::get('/inventory/incoming', [HomeController::class, 'incoming'])->name('inventory.incoming');
    // Outgoing
    Route::get('/inventory/outgoing', [HomeController::class, 'outgoing'])->name('inventory.outgoing');
    // Statistics
    Route::get('/reports/stats', [HomeController::class, 'statistics'])->name('report.statistics');
    // Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs');

    // Admin
    Route::middleware(['admin'])->group(function () {
        // Accounts
        Route::put('/accounts/restore', [AccountController::class, 'restore'])->name('accounts.restore');
        Route::resource('accounts', AccountController::class);

        // Suppliers
        Route::put('/suppliers/restore', [SupplierController::class, 'restore'])->name('suppliers.restore');
        Route::resource('suppliers', SupplierController::class);

        // Types
        Route::put('/types/restore', [TypeController::class, 'restore'])->name('types.restore');
        Route::resource('types', TypeController::class);
    });

    // Regular

    // Item
    Route::put('/items/restore', [ItemController::class, 'restore'])->name('items.restore');
    Route::resource('items', ItemController::class);

    // Purchase Orders
    Route::put('/purchase_orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase_orders.approve');
    Route::put('/purchase_orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase_orders.receive');
    Route::put('/purchase_orders/{purchaseOrder}/close', [PurchaseOrderController::class, 'close'])->name('purchase_orders.close');
    Route::resource('purchase_orders', PurchaseOrderController::class);

    // Release Orders
    Route::put('/release_orders/{releaseOrder}/approve', [ReleaseOrderController::class, 'approve'])->name('release_orders.approve');
    Route::put('/release_orders/{releaseOrder}/receive', [ReleaseOrderController::class, 'receive'])->name('release_orders.receive');
    Route::put('/release_orders/{releaseOrder}/close', [ReleaseOrderController::class, 'close'])->name('release_orders.close');
    Route::resource('release_orders', ReleaseOrderController::class);


    // API
    Route::get('/fetch/items/{supplier_id?}', function ($supplier_id) {
        if ($supplier_id == 0) {
            return Item::with('type')->get();
        }
        return Item::with('type')->where('supplier_id', $supplier_id)->get();
    })->name('fetch.items');
});
