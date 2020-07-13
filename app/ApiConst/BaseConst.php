<?php
namespace App\ApiConst;


class BaseConst
{
    public static $HTTP_SUCCESS_CODE = 200;
    public static $HTTP_SUCCESS_CREATE_CODE = 201;
    public static $HTTP_SUCCESS_DELETE_CODE = 204;
    public static $HTTP_ERROR_BAD_REQUEST_CODE = 400;
    public static $HTTP_ERROR_UNAUTHORIZED_CODE = 401;
    public static $HTTP_ERROR_FORBIDDEN_CODE = 403;
    public static $HTTP_ERROR_NOT_FOUND_CODE = 404;
    public static $HTTP_ERROR_UNPROCESABLE_ENTITY_CODE = 200;
    public static $HTTP_ERROR_INTERNAL_SERVER_ERROR_CODE = 500;

    // 登录接口
    public static $LOGIN_ERROR_MSG = '登录名或密码错误,请重试';
    public static $LOGIN_SUCCESS_MSG = '登录成功';
    public static $LOGIN_NO_EMAIL_MSG = '未填写邮箱';
    public static $LOGIN_EMAIL_TYPE_MSG = '邮箱类型不正确';
    public static $LOGIN_EMAIL_FORMAT_MSG = '邮箱格式不正确';
    public static $LOGIN_NO_PASSWORD_MSG = '未填写登录密码';
    public static $LOGIN_PASSWORD_TYPE_MSG = '登录密码类型不正确';


    // 权限相关接口
    public static $PERMISSION_ERROR_NO_MENU_DATA = '没有菜单数据';
    public static $PERMISSION_SUCCESS_GET_MENU_DATA = '菜单数据获取成功';

}
