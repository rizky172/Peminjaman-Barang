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


Route::group(["prefix"=>"/user"], function(){
    Route::get('/table', 'UserController@table');
    Route::get('/count', 'UserController@count');
    Route::get('/show/{id}', 'UserController@show');
    Route::get('/delete/{id}', 'UserController@delete');
    Route::post('/store', 'UserController@store');
    Route::post('/update', 'UserController@update');
    Route::post('/change', 'UserController@change');
});

Route::group(["prefix"=>"/pegawai"], function(){
    Route::get('/table', 'PegawaiController@table');
    Route::get('/count', 'PegawaiController@count');
    Route::get('/show/{id}', 'PegawaiController@show');
    Route::get('/delete/{id}', 'PegawaiController@delete');
    Route::post('/store', 'PegawaiController@store');
    Route::post('/update', 'PegawaiController@update');
    Route::post('/change', 'PegawaiController@change');
});


Route::group(["prefix"=>"/kategori"], function(){
    Route::get('/table', 'KategoriController@table');
    Route::get('/count', 'KategoriController@count');
    Route::get('/show/{id}', 'KategoriController@show');
    Route::get('/delete/{id}', 'KategoriController@delete');
    Route::post('/store', 'KategoriController@store');
    Route::post('/update', 'KategoriController@update');
});

Route::group(["prefix"=>"/barang"], function(){
    Route::get('/table', 'BarangController@table');
    Route::get('/count', 'BarangController@count');
    Route::get('/show/{id}', 'BarangController@show');
    Route::get('/delete/{id}', 'BarangController@delete');
    Route::get('/getKategori', 'BarangController@getKategori');
    Route::post('/store', 'BarangController@store');
    Route::post('/update', 'BarangController@update');
});

Route::group(["prefix"=>"/profil"], function(){
    Route::get('/show/{id}', 'ProfilController@show');
    Route::post('/update', 'ProfilController@update');
    Route::post('/change', 'ProfilController@change');
});

Route::group(["prefix"=>"/log"], function(){
    Route::get('/table', 'LogController@table');
    Route::get('/count', 'LogController@count');
});

Route::group(["prefix"=>"/pinjam"], function(){
    Route::get('/table', 'PinjamController@table');
    Route::get('/count', 'PinjamController@count');
    Route::get('/show/{id}', 'PinjamController@show');
    Route::get('/delete/{id}', 'PinjamController@delete');
    Route::post('/store', 'PinjamController@store');
    Route::post('/update', 'PinjamController@update');
    Route::get('/getBarangAll', 'PinjamController@getBarangAll');
});




