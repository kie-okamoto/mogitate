<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// 商品一覧
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// 商品登録
Route::get('/products/register', [ProductController::class, 'create'])->name('products.create');
Route::post('/products/register', [ProductController::class, 'store'])->name('products.store');

// 商品検索
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// 商品編集
Route::get('/products/{productId}', [ProductController::class, 'edit'])->name('products.edit');

// 商品更新
Route::put('/products/{productId}/update', [ProductController::class, 'update'])->name('products.update');

// 商品削除
Route::post('/products/{productId}/delete', [ProductController::class, 'destroy'])->name('products.destroy');
