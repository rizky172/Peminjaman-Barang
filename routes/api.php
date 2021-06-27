<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', 'AuthController@logout');
}); 

Route::group(["prefix"=>"/"], function(){
    Route::post("/login", "AuthController@login");
    Route::post("/register", "AuthController@register");
});


Route::group(["prefix"=>"/dummy"], function(){
    Route::get('/table', 'DummyController@table')->name('dummys');
    Route::get('/count', 'DummyController@count');
    Route::get('/search', 'DummyController@search');
    Route::get('/show/{id}', 'DummyController@show');
    Route::get('/delete/{id}', 'DummyController@delete');
    Route::post('/store', 'DummyController@store');
    Route::post('/update', 'DummyController@update');
});


Route::group(["prefix"=>"/user"], function(){
    Route::get('/table', 'UserController@table');
    Route::get('/count', 'UserController@count');
    Route::get('/show/{id}', 'UserController@show');
    Route::get('/delete/{id}', 'UserController@delete');
    Route::post('/store', 'UserController@store');
    Route::post('/update', 'UserController@update');
    Route::post('/change', 'UserController@change');
});
// test

Route::group(["prefix"=>"/kategori"], function(){
    Route::get('/table', 'KategoriController@table');
    Route::get('/count', 'KategoriController@count');
    Route::get('/show/{id}', 'KategoriController@show');
    Route::get('/delete/{id}', 'KategoriController@delete');
    Route::post('/store', 'KategoriController@store');
    Route::post('/update', 'KategoriController@update');
});

Route::group(["prefix"=>"/produk"], function(){
    Route::get('/table', 'ProdukController@table');
    Route::get('/count', 'ProdukController@count');
    Route::get('/show/{id}', 'ProdukController@show');
    Route::get('/delete/{id}', 'ProdukController@delete');
    Route::get('/getKategori', 'ProdukController@getKategori');
    Route::post('/store', 'ProdukController@store');
    Route::post('/update', 'ProdukController@update');
});







