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



    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        /**用户相关**/
        // 获取用户列表
        Route::get('users', 'ManagerController@getAdminUserList');
        // 新增用户
        Route::post('users', 'ManagerController@addAdminUser');
        // 修改用户状态
        Route::put('users/{uid}/state/{type}', 'ManagerController@changeAdminUserState');

        /**权限相关**/
        // 左侧菜单
        Route::get('menus', 'PermissionController@getLeftMenu');
    });
});


