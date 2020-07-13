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

Route::namespace('Api')->prefix('/private/v1/')->group(function() {
    Route::post('login', 'AuthController@login');

    Route::get('menus', 'PermissionController@getLeftMenu');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('users', 'ManagerController@getAdminUserList');

        // 权限相关
        // 左侧菜单
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});


