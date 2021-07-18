<?php

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

Auth::routes(['verify' => true]);

// route khusus untuk generate/download PDF
Route::group(["prefix"=>"/generatePDF"], function(){
    Route::get('/peminjaman/{id}', 'GeneratePDF@peminjaman');
});


Route::get('/{any}', function () {
    return view('layouts.app');
})->where('any', '^(?!api\/)[\/\w\.-]*');

