<?php

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/api/approval/po', function () {
            return response([
                'new_po_count' => PurchaseOrder::where('status_id', 1)->count()
            ], 200);
        })->name('get.po.count');
    });

});
