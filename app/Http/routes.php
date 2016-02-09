<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$router->get('/', function () {
    return [
        'version' => Config::get('app.version'),
    ];
});

$router->group(['prefix' => 'games'], function($router) {
    $router->get('/', 'GamesController@index');
    $router->post('/', 'GamesController@store');
    $router->get('/{id}', 'GamesController@find');
    $router->put('/{id}', 'GamesController@update');
    $router->delete('/{id}', 'GamesController@delete');
});

$router->group(['prefix' => 'game-scores'], function($router) {
    $router->get('/', 'GameScoresController@index');
    $router->post('/', 'GameScoresController@store');
    $router->get('/{id}', 'GameScoresController@find');
    $router->put('/{id}', 'GameScoresController@update');
    $router->delete('/{id}', 'GameScoresController@delete');
});



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
