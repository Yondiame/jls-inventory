<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['middleware' => 'cors'], function () use ($router) {
    //All the routes you want to allow CORS for

    $router->get('product/{id}', 'ProductController@show');
    $router->get('editProduct/{id}', 'ProductController@edit');
//    $router->get('products', 'ProductController@index');
    $router->put('updateProducts/{id}', 'ProductController@update');
    $router->get('products', 'ProductController@index');

    $router->options('/{any:.*}', function (Request $req) {
        return;
    });
});
