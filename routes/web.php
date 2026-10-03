<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use Illuminate\Support\Facades\Route;

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
// Route::controller(AuthController::class)->group(function(){
//     Route::get('/login', 'loginform')->name('loginform');
// });
// Route::get('/login', [ AuthController::class, 'loginform'])->name('loginform');
// Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', function(){
    return redirect()->route('loginform');
});

Route::controller(AuthController::class)->group(function (){
    Route::get('/login', 'loginform')->name('loginform');
    Route::post('/login', 'login')->name('form.submit');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware('admin.auth')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name("dashboard.index");
    Route::resource('categories', CategoryController::class);
    Route::resource('product', ProductController::class);
    Route::resource('stockIn', StockInController::class);
    Route::resource('stockOut', StockOutController::class);
    
    Route::get('/history', [HistoryController::class, 'index']) -> name('history.index');

});
