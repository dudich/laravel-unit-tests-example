<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::post('/user', [UsersController::class, 'update'])->middleware(['auth']);
Route::delete('/user', [UsersController::class, 'delete'])->middleware(['auth']);

Route::get('/products', [ProductsController::class, 'index']);
Route::post('/product', [ProductsController::class, 'create'])->middleware(['auth']);
Route::put('/product', [ProductsController::class, 'update'])->middleware(['auth']);
Route::delete('/product', [ProductsController::class, 'delete'])->middleware(['auth']);
