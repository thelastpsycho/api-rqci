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
$router->options('{any:.*}', function () {
    return response('', 200);
});

$router->get('/',function(){
    echo 'Hello from Lumen!';
});
$router->post('/login', 'AuthController@login');
$router->get('/me', 'AuthController@me');

$router->group(['middleware' => 'cors.middleware'], function () use ($router) {
    $router->post('/tes', function () {
        return response()->json(['message' => 'You are authorized!']);
    });


});

$router->group(['middleware' => ['auth.middleware','cors.middleware']], function () use ($router) {
    $router->get('/api/score/{room}','Rqci_controller@room_score');
    $router->get('/api/avg_score/','Rqci_controller@avg_score');
    $router->get('/api/list_defect/','Rqci_controller@list_defect');
});