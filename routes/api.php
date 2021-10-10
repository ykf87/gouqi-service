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
    'prefix'        => '/',
    'namespace'     => 'App\Http\Controllers\Api',
    // 'middleware'    => [],
    'as'            => 'api.'
], function(){
    Route::get('config', 'IndexController@config')->name('config');
    Route::get('cate', 'IndexController@cate')->name('cate');
    Route::get('list', 'IndexController@list')->name('list');
    Route::get('info/{id}', 'IndexController@info')->name('info')->where('id', '[0-9]+');
    Route::get('agreement', 'IndexController@agreement')->name('agreement');
	Route::post('sigin', 'UserController@sigin')->name('sigin');
	Route::post('login', 'UserController@login')->name('login');
	Route::post('help', 'IndexController@help')->name('help');
	Route::post('checkuid', 'CheckController@uid')->name('checkuid');

	Route::post('checkuid', 'AutojsConteoller@index')->name('autojs');

    // 需要权限认证的请求
    Route::group([
	    'middleware'    => ['jwt'],
	    // 'as'            => 'user.'
	], function(){
	    Route::get('center', 'UserController@index')->name('index');
	    Route::post('reset', 'UserController@reset')->name('reset');
	    Route::get('watch', 'UserController@watch')->name('watch');
	    Route::get('history', 'UserController@history')->name('history');
	    Route::get('heart', 'UserController@watch')->name('heart');
	    Route::post('heart', 'UserController@heart')->name('heart');
	    Route::post('plaied', 'UserController@palied')->name('palied');
	    Route::get('jifen', 'UserController@jifen')->name('jifen');
	    Route::get('task', 'UserController@tasks')->name('task');

	    Route::post('tixian', 'UserController@tixian')->name('tixian');
	    Route::post('card', 'UserController@card')->name('card');
	    Route::get('card', 'UserController@mycard')->name('mycard');
	    Route::get('bank', 'UserController@bank')->name('bank');
	    Route::get('withdraw', 'UserController@withdraw')->name('withdraw');
	});
});
