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

Route::get('/', 'App\Http\Controllers\Main@index');
Route::post('/upload-csv', 'App\Http\Controllers\Main@uploadCsv');
Route::post('/get-customer','App\Http\Controllers\Main@getCustomer');
