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
Route::any('/ajax/addListing', [Ajax::class, 'addListing']);
Route::any('/ajax/listExisting', [Ajax::class, 'listExisting']);
Route::any('/ajax/deleteProduct', [Ajax::class, 'removeListing']);
Route::any('/dynamic', [Home::class, 'dynamic']);
Route::any('/listings', [Home::class, 'showListings']);
Route::any('/exactSearch', [Home::class, 'exactSearchPage']);
Route::any('/rankedSearch', [Home::class, 'rankedSearchPage']);
Route::any('/ajax/exactSearch', [Ajax::class, 'exactSearch']);
Route::any('/ajax/rankedSearch', [Ajax::class, 'rankedSearch']);
Route::any('/ajax/serialize', [Ajax::class, 'listify']);
Route::any('/ajax/detailList', [Ajax::class, 'detailList']);
Route::any('/ajax/typeList', [Ajax::class, 'typeList']);
Route::any('/ajax/nameList', [Ajax::class, 'nameList']);
Route::any('/ajax/attributeList', [Ajax::class, 'attributeList']);
Route::any('/ajax/featureList', [Ajax::class, 'featureList']);
Route::any('/ajax/atrValList', [Ajax::class, 'getAtrVals']);
Route::any('/ajax/ftrValList', [Ajax::class, 'getFtrVals']);
Route::any('/ajax/form/atr', [Ajax::class, 'getFormAtr']);
Route::any('/ajax/form/ftr', [Ajax::class, 'getFormFtr']);
Route::any('/ajax/form/cmpr', [Ajax::class, 'getFormCmpr']);
