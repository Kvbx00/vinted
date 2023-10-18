<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VintedController;

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

Route::get('/', [VintedController::class, 'searchProducts'])->name('search.products');
Route::get('/sort', [VintedController::class, 'sortProducts'])->name('sort.products');
