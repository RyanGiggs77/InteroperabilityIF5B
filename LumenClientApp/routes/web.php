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

$router->get('/posts/get-request-json', 'PostsController@getRequestJson');
$router->get('/posts/get-request-xml','PostsController@getRequestXml');
$router->get('/posts/post-request-json','PostsController@postRequestJson');
$router->get('/posts/get-request-xml','PostsController@getRequestXml');
$router->get('/posts/post-request-xml','PostsController@postRequestXml');
$router->get('/posts/show-request-xml/{id}','PostsController@showRequestXml');
