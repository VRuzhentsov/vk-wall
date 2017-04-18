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

$api->version('v1', ['namespace' => '\App\Http\Controllers', 'middleware' => 'api'], function ($api) {
    /** @var \Dingo\Api\Routing\Router $api */

    $api->group(['middleware' => 'auth', 'provider' => 'basic'], function ($api) {
        /** @var \Dingo\Api\Routing\Router $api */
        $api->get('user', 'API\UserAPIController@authUser');
        $api->resource('users', App\Http\Controllers\API\UserAPIController::class);
    });

    $api->post('login', 'Auth\LoginController@login');
    $api->post('logout', 'Auth\LoginController@logout')->name('logout');

    $api->post('register', 'Auth\RegisterController@register');

    $api->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $api->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    $api->post('password/reset', 'Auth\ResetPasswordController@reset');

});
