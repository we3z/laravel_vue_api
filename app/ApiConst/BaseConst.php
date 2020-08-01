<?php
namespace App\ApiConst;


class BaseConst
{
    const HTTP_SUCCESS_CODE = 200;
    const HTTP_SUCCESS_CREATE_CODE = 201;
    const HTTP_SUCCESS_DELETE_CODE = 204;
    const HTTP_ERROR_BAD_REQUEST_CODE = 400;
    const HTTP_ERROR_UNAUTHORIZED_CODE = 401;
    const HTTP_ERROR_FORBIDDEN_CODE = 403;
    const HTTP_ERROR_NOT_FOUND_CODE = 404;
    const HTTP_ERROR_UNPROCESABLE_ENTITY_CODE = 200;
    const HTTP_ERROR_INTERNAL_SERVER_ERROR_CODE = 500;

    // 登录接口
    const LOGIN_ERROR_MSG = '登录名或密码错误,请重试';
    const LOGIN_SUCCESS_MSG = '登录成功';
    const LOGIN_NO_EMAIL_MSG = '未填写邮箱';
    const LOGIN_EMAIL_TYPE_MSG = '邮箱类型不正确';
    const LOGIN_EMAIL_FORMAT_MSG = '邮箱格式不正确';
    const LOGIN_NO_PASSWORD_MSG = '未填写登录密码';
    const LOGIN_PASSWORD_TYPE_MSG = '登录密码类型不正确';

    /**用户列表相关**/
    // 查询用户列表
    const USER_ERROR_NO_PAGE_SIZE = '没有page_size参数';
    const USER_ERROR_PAGE_SIZE_NOT_INT = 'pagesize参数格式不正确';
    const USER_ERROR_PAGE_SIZE_MIN = 'pagesize参数最小值为1';
    const USER_ERROR_NO_PAGE_NUM = '没有pagenum参数';
    const USER_ERROR_PAGE_NUM_NOT_INT = 'pagenum参数格式不正确';
    const USER_ERROR_PAGE_NUM_MIN = 'ppagenum参数最小值为1';
    const USER_SUCCESS_GET_DATA = '获取数据成功';
    // 新增用户
    const ADD_USER_ERROR_NO_USERNAME = '没有输入用户名';
    const ADD_USER_ERROR_USERNAME_MIN_AND_MAX = '用户名长度最小值为3，最大值为15';
    const ADD_USER_ERROR_NO_PASSWORD = '没有输入密码';
    const ADD_USER_ERROR_PASSWORD_MIN_AND_MAX = '密码长度最小值为3，最大值为15';
    const ADD_USER_ERROR_NO_EMAIL = '没有输入邮箱';
    const ADD_USER_ERROR_EMAIL_FORMAT = '邮箱格式不正确';
    const ADD_USER_ERROR_EMAIL_UNIQUE = '邮箱已存在';
    const ADD_USER_ERROR_RESULT = '用户添加失败,请重试';
    const ADD_USER_SUCCESS_RESULT = '用户添加成功';
    // 修改用户状态
    const CHANGE_USER_STATUS_ERROR_NO_UID = '参数不正确,缺少用户ID';
    const CHANGE_USER_STATUS_ERROR_UID_FORMAT = '参数用户ID类型不正确';
    const CHANGE_USER_STATUS_ERROR_NO_TYPE = '参数不正确,缺少修改类型';
    const CHANGE_USER_STATUS_ERROR_TYPE_FORMAT = '参数修改类型不正确';
    const CHANGE_USER_STATUS_ERROR_USER_DATA = '用户信息不正确';
    const CHANGE_USER_STATUS_SUCCESS_RESULT = '用户状态修改成功';
    const CHANGE_USER_STATUS_ERROR_RESULT = '用户状态修改失败,请重试';
    // 查询用户状态
    const GET_USER_INFO_ERROR_NO_ID = '参数不正确,缺少用户ID';
    const GET_USER_INFO_ERROR_ID_FORMAT = '参数用户ID格式不正确';
    const GET_USER_INFO_ERROR_RESULT = '获取用户信息失败,请重试';
    const GET_USER_INFO_SUCCESS_RESULT = '获取用户信息成功';
    // 修改用户信息
    const EDIT_USER_INFO_ERROR_NO_ID = '参数不正确,缺少用户ID';
    const EDIT_USER_INFO_ERROR_ID_FORMAT = '参数用户ID格式不正确';
    const EDIT_USER_INFO_ERROR_NO_EMAIL = '参数不正确,缺少用户邮箱';
    const EDIT_USER_INFO_ERROR_EMAIL_FORMAT = '参数用户邮箱格式不正确';
    const EDIT_USER_INFO_ERROR_EMAIL_UNIQUE = '参数用户邮箱唯一';
    const EDIT_USER_INFO_ERROR_NO_MOBILE = '参数不正确,缺少用户手机号';
    const EDIT_USER_INFO_ERROR_RESULT = '用户信息编辑失败,请重试';
    const EDIT_USER_INFO_SUCCESS_RESULT = '用户信息编辑成功';
    // 删除用户
    const DELETE_USER_ERROR_NO_ID = '参数不正确,缺少用户ID';
    const DELETE_USER_ERROR_ID_FORMAT = '参数用户ID格式不正确';
    const DELETE_USER_ERROR_RESULT = '删除用户信息失败,请重试';
    const DELETE_USER_SUCCESS_RESULT = '删除用户信息成功';
    // 分配用户角色
    const ALLOW_USER_ROLE_ERROR_NO_USER_ID = '参数不正确,缺少用户ID';
    const ALLOW_USER_ROLE_ERROR_USER_ID_FORMAT = '参数用户ID格式不正确';
    const ALLOW_USER_ROLE_ERROR_NO_ROLE_ID = '参数不正确,缺少角色ID';
    const ALLOW_USER_ROLE_ERROR_ROLE_ID_FORMAT = '参数角色ID格式不正确';
    const ALLOW_USER_ROLE_ERROR_NO_ROLE_INFO = '角色信息不正确,请重试';
    const ALLOW_USER_ROLE_ERROR_NO_USER_INFO = '用户信息不正确,请重试';
    const ALLOW_USER_ROLE_ERROR_RESULT = '用户角色分配失败,请重试';
    const ALLOW_USER_ROLE_SUCCESS_RESULT = '用户角色分配成功';

    /** 权限相关接口 **/
    // 获取权限列表
    const PERMISSION_ERROR_NO_TYPE = '参数不正确,确实type参数';
    const PERMISSION_ERROR_TYPE_FORMAT = '参数type格式不正确';
    const PERMISSION_ERROR_TYPE_USELESS = '参数type值不正确';
    const PERMISSION_ERROR_RESULT = '获取所有权限列表失败，请重试';
    const PERMISSION_SUCCESS_RESULT = '获取所有权限列表成功';
    // 获取左侧菜单
    const PERMISSION_ERROR_NO_MENU_DATA = '没有菜单数据';
    const PERMISSION_SUCCESS_GET_MENU_DATA = '菜单数据获取成功';
    /** 角色相关 **/
    // 获取角色列表
    const ROLE_SUCCESS_RESULT = '角色列表获取成功';
    // 添加新角色
    const ROLE_ADD_ERROR_NO_AUTH_NAME = '参数不正确,缺少权限名称';
    const ROLE_ADD_ERROR_AUTH_NAME_FORMAT = '参数权限名称格式不正确';
    const ROLE_ADD_ERROR_AUTH_NAME_MIN = '参数权限名称长度在3到25之间';
    const ROLE_ADD_ERROR_AUTH_NAME_MAX = '参数权限名称长度在3到25之间';
    const ROLE_ADD_ERROR_AUTH_NAME_UNIQUE = '参数权限名称长度在3到25之间';
    const ROLE_ADD_ERROR_NO_AUTH_DESC = '参数不正确,缺少权限描述';
    const ROLE_ADD_ERROR_AUTH_DESC_FORMAT = '参数权限描述格式不正确';
    const ROLE_ADD_ERROR_AUTH_DESC_MIN = '参数权限描述长度在3到25之间';
    const ROLE_ADD_ERROR_AUTH_DESC_MAX = '参数权限描述长度在3到25之间';
    const ROLE_ADD_ERROR_RESULT = '新增角色失败,请重试';
    const ROLE_ADD_SUCCESS_RESULT = '新增角色成功';
    // 查询角色
    const ROLE_GET_INFO_ERROR_NO_ID = '参数不正确,缺少角色ID';
    const ROLE_GET_INFO_ERROR_ID_FORMAT = '参数角色ID格式不正确';
    const ROLE_GET_INFO_ERROR_RESULT = '角色查询失败,请重试';
    const ROLE_GET_INFO_SUCCESS_RESULT = '角色查询成功';
    // 编辑角色
    const ROLE_EDIT_ERROR_NO_ID = "参数不正确,缺少角色ID";
    const ROLE_EDIT_ERROR_ID_FORMAT = '参数角色ID格式不正确';
    const ROLE_EDIT_ERROR_NO_NAME= "参数不正确,缺少角色名称";
    const ROLE_EDIT_ERROR_NAME_FORMAT = '参数角色名称格式不正确';
    const ROLE_EDIT_ERROR_DESC_FORMAT = '参数角色描述格式不正确';
    const ROLE_EDIT_ERROR_NO_ROLE = '角色信息不正确';
    const ROLE_EDIT_ERROR_RESULT = '角色编辑失败,请重试';
    const ROLE_EDIT_SUCCESS_RESULT = '角色编辑成功';
    // 删除角色
    const ROLE_DELETE_ERROR_NO_ID = '参数不正确,缺少角色ID';
    const ROLE_DELETE_ERROR_ID_FORMAT = '参数角色ID格式不正确';
    const ROLE_DELETE_ERROR_RESULT = '角色删除失败,请重试';
    const ROLE_DELETE_SUCCESS_RESULT = '角色删除成功';
    // 删除角色权限
    const ROLE_DELETE_RIGHT_ERROR_NO_ROLE_ID = "参数不正确,缺少角色ID";
    const ROLE_DELETE_RIGHT_ERROR_ROLE_ID_FORMAT = "参数角色ID格式不正确";
    const ROLE_DELETE_RIGHT_ERROR_NO_RIGHT_ID = "参数不正确,缺少权限ID";
    const ROLE_DELETE_RIGHT_ERROR_RIGHT_ID_FORMAT = "参数权限ID格式不正确";
    const ROLE_DELETE_RIGHT_ERROR_NO_ROLE_INFO = "用户角色不存在,请重试";
    const ROLE_DELETE_RIGHT_ERROR_NO_RIGHT_INFO = "权限不存在,请重试";
    const ROLE_DELETE_RIGHT_ERROR_ROLE_NO_RIGHT = "该角色当前没有权限,请重试";
    const ROLE_DELETE_RIGHT_ERROR_ROLE_NOT_HAVE_RIGHT = "该角色不存在此权限,请重试";
    const ROLE_DELETE_RIGHT_ERROR_RIGHT_LEVEL = "权限级别不正确,请重试";
    const ROLE_DELETE_RIGHT_ERROR_RESULT = '角色编辑失败,请重试';
    const ROLE_DELETE_RIGHT_SUCCESS_RESULT = '角色编辑成功';
    // 分配角色权限
    const ROLE_ALLOW_RIGHT_ERROR_NO_ROLE_ID = "参数不正确,缺少角色ID";
    const ROLE_ALLOW_RIGHT_ERROR_ROLE_ID_FORMAT = "参数角色ID格式不正确";
    const ROLE_ALLOW_RIGHT_ERROR_NO_RIGHT_IDS = "参数不正确,缺少权限ID";
    const ROLE_ALLOW_RIGHT_ERROR_RIGHT_IDS_FORMAT = "参数权限ID格式不正确";
    const ROLE_ALLOW_RIGHT_ERROR_NO_ROLE_INFO = "用户角色不存在,请重试";
    const ROLE_ALLOW_RIGHT_ERROR_NO_RIGHT_INFO = "权限不存在,请重试";
    const ROLE_ALLOW_RIGHT_ERROR_RESULT = '角色分配权限失败,请重试';
    const ROLE_ALLOW_RIGHT_SUCCESS_RESULT = '角色分配权限成功';

}
