<?php

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

/*
 * Authentication routes
 */
$router->group(['namespace' => 'Auth'], function() use ($router){
    $router->post('/login', ['as' => 'login', 'uses' => 'AuthController@login']);
    $router->post('/register', ['as' => 'register', 'uses' => 'AuthController@register']);
});
/*
 * hash route 
 */
$router->get('/hash[/{stringToBeHashed}]', ['as' => 'test.hash', 'uses' => 'HashController@hash']);
