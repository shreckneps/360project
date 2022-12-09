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

    Route::any('/ajax/addOffer', 'addOffer');
    Route::any('/ajax/acceptOffer', 'acceptOffer');
    Route::any('/ajax/acceptOffer', 'acceptOffer');
    Route::any('/ajax/dismissOffer', 'dismissOffer');
    Route::any('/ajax/offerOtherName', 'offerPartner');
    Route::any('/ajax/rejectOffer', 'rejectOffer');

    Route::any('/ajax/detailList', 'detailList');

    Route::any('/ajax/attributeList', 'attributeList');
    Route::any('/ajax/atrValList', 'getAtrVals');
    Route::any('/ajax/featureList', 'featureList');
    Route::any('/ajax/fldValList', 'getFldVals');
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
    Route::any('/offers', 'showOffers');
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
