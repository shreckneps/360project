<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounts;
use App\Http\Controllers\Ajax;
use App\Http\Controllers\Datagen;
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

Route::controller(Accounts::class)->group(function () {
    Route::any('/login', 'login');
    Route::any('/register', 'register');
});

Route::controller(Ajax::class)->group(function () {
    Route::any('/ajax/addNeedmap', 'addNeedmap');

    Route::any('/ajax/addListing', 'addListing');
    Route::any('/ajax/listExisting', 'listExisting');
    Route::any('/ajax/addProduct', 'addProduct');
    Route::any('/ajax/deleteProduct', 'removeListing');

    Route::any('/ajax/detailList', 'detailList');

    Route::any('/ajax/attributeList', 'attributeList');
    Route::any('/ajax/atrValList', 'getAtrVals');
    Route::any('/ajax/featureList', 'featureList');
    Route::any('/ajax/ftrValList', 'getFtrVals');
    Route::any('/ajax/nameList', 'nameList');
    Route::any('/ajax/typeList', 'typeList');

    Route::any('/ajax/cmpr', 'getFormCmpr');
    Route::any('/ajax/form/{type}', 'getForm');

    Route::any('/ajax/exactSearch', 'exactSearch');
    Route::any('/ajax/needSearch', 'needSearch');
    Route::any('/ajax/rankedSearch', 'rankedSearch');
    Route::any('/ajax/userProducts', 'userProducts');


    Route::any('/ajax/serialize', 'listify');
});

Route::controller(Home::class)->group(function () {
    Route::any('/', 'dashboard');
    Route::any('/productlist', 'productlist');
    Route::any('/add', 'addProduct');
    Route::any('/dynamic', 'dynamic');
    Route::any('/listings', 'showListings');
    Route::any('/exactSearch', 'exactSearchPage');
    Route::any('/rankedSearch', 'rankedSearchPage');
    Route::any('/needSearch', 'needSearchPage');
    Route::any('/testPage', 'testPage');
    Route::any('/datagen', 'datagenPage');
    Route::any('/addNeedmap', 'needmapPage');
});

Route::controller(Datagen::class)->group(function () {
    Route::any('/datagen/author', 'genAuthors');
    Route::any('/datagen/home', 'genHomes');
    Route::any('/datagen/tech', 'genTech');
});
/*
Route::any('/login', [Accounts::class, 'login']);
Route::any('/register', [Accounts::class, 'register']);


Route::any('/ajax/addProduct', [Ajax::class, 'addProduct']);
Route::any('/ajax/addListing', [Ajax::class, 'addListing']);
Route::any('/ajax/listExisting', [Ajax::class, 'listExisting']);
Route::any('/ajax/deleteProduct', [Ajax::class, 'removeListing']);
Route::any('/ajax/exactSearch', [Ajax::class, 'exactSearch']);
Route::any('/ajax/rankedSearch', [Ajax::class, 'rankedSearch']);
Route::any('/ajax/needSearch', [Ajax::class, 'needSearch']);
Route::any('/ajax/serialize', [Ajax::class, 'listify']);
Route::any('/ajax/detailList', [Ajax::class, 'detailList']);
Route::any('/ajax/typeList', [Ajax::class, 'typeList']);
Route::any('/ajax/nameList', [Ajax::class, 'nameList']);
Route::any('/ajax/attributeList', [Ajax::class, 'attributeList']);
Route::any('/ajax/featureList', [Ajax::class, 'featureList']);
Route::any('/ajax/atrValList', [Ajax::class, 'getAtrVals']);
Route::any('/ajax/ftrValList', [Ajax::class, 'getFtrVals']);
Route::any('/ajax/form/{type}', [Ajax::class, 'getForm']);
Route::any('/ajax/cmpr', [Ajax::class, 'getFormCmpr']);
Route::any('/ajax/userProducts', [Ajax::class, 'userProducts']);
Route::any('/ajax/addNeedmap', [Ajax::class, 'addNeedmap']);

Route::any('/datagen/author', [Datagen::class, 'genAuthors']);

Route::any('/', [Home::class, 'dashboard']);
Route::any('/productlist', [Home::class, 'productlist']);
Route::any('/add', [Home::class, 'addProduct']);
Route::any('/dynamic', [Home::class, 'dynamic']);
Route::any('/listings', [Home::class, 'showListings']);
Route::any('/exactSearch', [Home::class, 'exactSearchPage']);
Route::any('/rankedSearch', [Home::class, 'rankedSearchPage']);
Route::any('/needSearch', [Home::class, 'needSearchPage']);
Route::any('/testPage', [Home::class, 'testPage']);
Route::any('/datagen', [Home::class, 'datagenPage']);
Route::any('/addNeedmap', [Home::class, 'needmapPage']);

 */
