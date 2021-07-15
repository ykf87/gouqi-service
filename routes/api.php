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
    'middleware'    => [],
    'as'            => 'api.'
], function(){
    Route::get('config', 'IndexController@config')->name('config');
    Route::get('cate', 'IndexController@cate')->name('cate');

    // 需要权限认证的请求
    Route::group([
	    'prefix'        => '',
	    'namespace'     => 'Users',
	    'middleware'    => ['jwt'],
	    'as'            => 'user.'
	], function(){
	    Route::get('sigin', 'UserController@sigin')->name('sigin');
	    Route::post('logoin', 'UserController@login')->name('login');
	});
});
