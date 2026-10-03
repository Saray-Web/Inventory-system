<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\productController;
use App\Http\Controllers\stockInController;
use App\Http\Controllers\stockOutController;
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
// Route::controller(Authcontroller::class)->group(function(){
//     Route::get('/login', 'loginform')->name('loginform');
// });
// Route::get('/login', [ Authcontroller::class, 'loginform'])->name('loginform');
// Route::post('/login', [Authcontroller::class, 'login'])->name('login.submit');
// Route::post('/logout', [Authcontroller::class, 'logout'])->name('logout');
Route::get('/', function(){
    return redirect()->route('loginform');
});

Route::controller(Authcontroller::class)->group(function (){
    Route::get('/login', 'loginform')->name('loginform');
    Route::post('/login', 'login')->name('form.submit');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware('admin.auth')->group(function(){
    Route::get('/dashboard', [dashboardController::class, 'index'])->name("dashboard.index");
    Route::resource('categories', CategoryController::class);
    Route::resource('product', productController::class);
    Route::resource('stockIn', stockInController::class);
    Route::resource('stockOut', stockOutController::class);
    
    Route::get('/history', [HistoryController::class, 'index']) -> name('history.index');

});


