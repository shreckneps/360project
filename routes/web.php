<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounts;
use App\Http\Controllers\Ajax;
use App\Http\Controllers\Home;

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

Route::any('/', [Home::class, 'dashboard']);
Route::any('/login', [Accounts::class, 'login']);
Route::any('/register', [Accounts::class, 'register']);
Route::any('/productlist', [Home::class, 'productlist']);
Route::any('/add', [Home::class, 'addProduct']);
Route::any('/ajax/addProduct', [Ajax::class, 'addProduct']);
Route::any('/dynamic', [Home::class, 'dynamic']);
Route::any('/ajax/serialize', [Ajax::class, 'listify']);
Route::any('/ajax/form/atr', [Ajax::class, 'getFormAtr']);
Route::any('/ajax/form/ftr', [Ajax::class, 'getFormFtr']);
