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
        // 查询用户详情
        Route::get('users/{id}', 'ManagerController@getAdminUserInfo');
        // 修改用户信息
        Route::put('users/{id}', 'ManagerController@editAdminUserInfo');
        // 删除用户
        Route::delete('users/{id}', 'ManagerController@deleteAdminUser');
        /**权限相关**/
        // 左侧菜单
        // 获取所有权限列表
        Route::get('rights/{type}', 'PermissionController@getPermissionList');
        Route::get('menus', 'PermissionController@getLeftMenu');
        /**角色相关**/
        // 获取角色列表
        Route::get('roles', 'RoleController@getRoleList');
        // 添加角色
        Route::post('roles', 'RoleController@addRole');
        // 查询角色基础信息
        Route::get('roles/{id}', 'RoleController@getRoleInfo');
    });
});


