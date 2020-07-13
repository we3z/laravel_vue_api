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

    /**用户列表相关**/
    // 查询用户列表
    public static $USER_ERROR_NO_PAGE_SIZE = '没有page_size参数';
    public static $USER_ERROR_PAGE_SIZE_NOT_INT = 'pagesize参数格式不正确';
    public static $USER_ERROR_PAGE_SIZE_MIN = 'pagesize参数最小值为1';
    public static $USER_ERROR_NO_PAGE_NUM = '没有pagenum参数';
    public static $USER_ERROR_PAGE_NUM_NOT_INT = 'pagenum参数格式不正确';
    public static $USER_ERROR_PAGE_NUM_MIN = 'ppagenum参数最小值为1';
    public static $USER_SUCCESS_GET_DATA = '获取数据成功';
    // 新增用户
    public static $ADD_USER_ERROR_NO_USERNAME = '没有输入用户名';
    public static $ADD_USER_ERROR_USERNAME_MIN_AND_MAX = '用户名长度最小值为3，最大值为15';
    public static $ADD_USER_ERROR_NO_PASSWORD = '没有输入密码';
    public static $ADD_USER_ERROR_PASSWORD_MIN_AND_MAX = '密码长度最小值为3，最大值为15';
    public static $ADD_USER_ERROR_NO_EMAIL = '没有输入邮箱';
    public static $ADD_USER_ERROR_EMAIL_FORMAT = '邮箱格式不正确';
    public static $ADD_USER_ERROR_EMAIL_UNIQUE = '邮箱已存在';
    public static $ADD_USER_ERROR_RESULT = '用户添加失败,请重试';
    public static $ADD_USER_SUCCESS_RESULT = '用户添加成功';
    // 修改用户状态
    public static $CHANGE_USER_STATUS_ERROR_NO_UID = '参数不正确,缺少用户ID';
    public static $CHANGE_USER_STATUS_ERROR_UID_FORMAT = '参数用户ID类型不正确';
    public static $CHANGE_USER_STATUS_ERROR_NO_TYPE = '参数不正确,缺少修改类型';
    public static $CHANGE_USER_STATUS_ERROR_TYPE_FORMAT = '参数修改类型不正确';
    public static $CHANGE_USER_STATUS_ERROR_USER_DATA = '用户信息不正确';
    public static $CHANGE_USER_STATUS_SUCCESS_RESULT = '用户状态修改成功';
    public static $CHANGE_USER_STATUS_ERROR_RESULT = '用户状态修改失败,请重试';


    // 权限相关接口
    public static $PERMISSION_ERROR_NO_MENU_DATA = '没有菜单数据';
    public static $PERMISSION_SUCCESS_GET_MENU_DATA = '菜单数据获取成功';

}
