<?php

use App\Http\Controllers\Api\V1\Admin\ProductController;
use App\Http\Controllers\Api\V1\Admin\WarehouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Auth route start*/
Route::group(['prefix' => 'v1/admin'], function () {

    /*warehouse route start*/
    Route::get('warehouse', [WarehouseController::class, 'index']);
    Route::post('warehouse', [WarehouseController::class, 'store'])->name('warehouse.store');
    Route::get('warehouse/{id}', [WarehouseController::class, 'show']);
    Route::put('warehouse/{id}', [WarehouseController::class, 'update'])->name('warehouse.update');
    Route::delete('warehouse/{id}', [WarehouseController::class, 'destroy']);
    /*warehouse route start*/

    /*product route start*/
    Route::get('product', [ProductController::class, 'index']);
    Route::post('product', [ProductController::class, 'store'])->name('product.store');
    Route::get('product/{id}', [ProductController::class, 'show']);
    Route::put('product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('product/{id}', [ProductController::class, 'destroy']);
    /*product route end*/
});
/*Auth route end*/