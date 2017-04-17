<?php

use Illuminate\Http\Request;

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


$api = app(Dingo\Api\Routing\Router::class);

$api->version('v1', function ($api) {
    /** @var \Dingo\Api\Routing\Router $api */
    $api->resource('users', App\Http\Controllers\API\UserAPIController::class);
});
