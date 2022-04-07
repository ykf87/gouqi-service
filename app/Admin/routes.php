<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('products', ProductController::class);
    $router->resource('sigin-products', SiginProductController::class);
    $router->resource('sigin-logs', SiginLogController::class);
    $router->resource('sigin-tasks', SiginTaskController::class);

    $router->resource('users', UsersController::class);
});
