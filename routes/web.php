<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Campaign;
use App\Http\Controllers\Report;
use App\Http\Controllers\Authenticate;
use App\Http\Controllers\PaymentRequest;
use App\Http\Controllers\Category;

Route::get('/login', [Authenticate::class, 'login'])->name('login');
Route::post('/authenticate', [Authenticate::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [Authenticate::class, 'logout'])->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/', [Dashboard::class, 'index'])->name('dashboard');
  Route::get('/data-chart', [Dashboard::class, 'getDataChart'])->name('get-data-chart');

  Route::prefix('campaigns')->group(function (){
    Route::get('/', [Campaign::class, 'index'])->name('campaigns');
    Route::get('/create', [Campaign::class, 'create'])->name('campaign-create');
    Route::post('/store', [Campaign::class, 'store'])->name('campaign-store');
    Route::get('/{id}', [Campaign::class, 'edit'])->name('campaign-edit');
    Route::put('/{id}', [Campaign::class, 'update'])->name('campaign-update');
  });

  Route::prefix('categories')->group(function (){
    Route::get('/', [Category::class, 'index'])->name('categories');
    Route::get('/create', [Category::class, 'create'])->name('category-create');
    Route::post('/store', [Category::class, 'store'])->name('category-store');
  });

  Route::prefix('reports')->group(function (){
    Route::get('/performance', [Report::class, 'performance'])->name('report-performance');
    Route::get('/order', [Report::class, 'order'])->name('report-order');
    Route::get('/order-export', [Report::class, 'exportReportOrder'])->name('report-order-export');
  });

  Route::prefix('payment')->group(function (){
    Route::get('/request', [PaymentRequest::class, 'index'])->name('payment-request');
    Route::put('/update-status', [PaymentRequest::class, 'changeStatus'])->name('payment-update-status');
  });
});
