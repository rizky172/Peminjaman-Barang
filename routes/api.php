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

Route::group(["prefix"=>"/profil"], function(){
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('/show/{id}', 'ProfilController@show');
        Route::post('/update', 'ProfilController@update');
        Route::post('/change', 'ProfilController@change');

        Route::get('/table', 'ProfilController@table');
        Route::get('/count', 'ProfilController@count');
    });
});

Route::group(["prefix"=>"/user"], function(){
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('/table', 'UserController@table');
        Route::get('/count', 'UserController@count');
        Route::get('/show/{id}', 'UserController@show');
        Route::get('/delete/{id}', 'UserController@delete');
        Route::post('/store', 'UserController@store');
        Route::post('/update', 'UserController@update');
        Route::post('/change', 'UserController@change');
    });
});

Route::group(["prefix"=>"/pegawai"], function(){
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('/table', 'PegawaiController@table');
        Route::get('/count', 'PegawaiController@count');
        Route::get('/show/{id}', 'PegawaiController@show');
        Route::get('/delete/{id}', 'PegawaiController@delete');
        Route::post('/store', 'PegawaiController@store');
        Route::post('/update', 'PegawaiController@update');
        Route::post('/change', 'PegawaiController@change');
    });
});


Route::group(["prefix"=>"/mobil"], function(){
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('/table', 'MobilController@table');
        Route::get('/count', 'MobilController@count');
        Route::get('/show/{id}', 'MobilController@show');
        Route::get('/delete/{id}', 'MobilController@delete');
        Route::post('/store', 'MobilController@store');
        Route::post('/update', 'MobilController@update');
    });
});

Route::group(["prefix"=>"/peminjaman"], function(){
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('/table', 'PeminjamanController@table');
        Route::get('/count', 'PeminjamanController@count');
        Route::get('/delete/{id}', 'PeminjamanController@delete');
        Route::post('/store', 'PeminjamanController@store');
        Route::get('/getMobilAll', 'PeminjamanController@getMobilAll');

        Route::post('/getHistoryById', 'PeminjamanController@getHistoryById');
        Route::get('/countHistory/{id}', 'PeminjamanController@countHistory');
    });
});

Route::group(["prefix"=>"/cek_peminjaman"], function(){
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('/table', 'CheckPeminjamanController@table');
        Route::get('/count', 'CheckPeminjamanController@count');
        Route::get('/delete/{id}', 'CheckPeminjamanController@delete');
        Route::get('/show/{id}', 'CheckPeminjamanController@show');
        Route::post('/setStatus', 'CheckPeminjamanController@setStatus');

        Route::post('/getHistoryById', 'CheckPeminjamanController@getHistoryById');
        Route::get('/countHistory/{id}', 'CheckPeminjamanController@countHistory');
        Route::get('/grafik/{id}', 'CheckPeminjamanController@grafik');
        Route::get('/getMobilAll', 'CheckPeminjamanController@getMobilAll');

        Route::post('/getReportBulanan', 'CheckPeminjamanController@getReportBulanan');
    });
});


