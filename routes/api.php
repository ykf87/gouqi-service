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

Route::group([
    'prefix'        => '',
    'namespace'     => 'App\Http\Controllers',
    'middleware'    => [],
    'as'            => 'api.'
], function(){
    Route::get('', 'ExcelController@index')->name('index');
    Route::post('reader', 'ExcelController@reader')->name('reader');
    Route::post('import', 'ExcelController@imports')->name('import');
    Route::post('output', 'ExcelController@output')->name('output');
});
