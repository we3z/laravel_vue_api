<?php

namespace App\Http\Controllers\Api;

use App\ApiConst\BaseConst;
use App\Http\Controllers\Controller;
use App\Service\ManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManagerController extends BaseController
{
    /**
     * @auther zlq
     * @create_time 2020/7/13 17:29
     * @description 获取管理员列表
     * @param Request $request
     * @param ManagerService $managerService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminUserList(Request $request, ManagerService $managerService)
    {
        $param = $request->all(['query', 'pagesize', 'pagenum']);
        // 验证数据
        $validator = Validator::make($request->all(), [
            'pagesize' => 'required|integer|min:1',
            'pagenum' => 'required|integer|min:1'
        ], [
            'pagesize.required' => BaseConst::$USER_ERROR_NO_PAGE_SIZE,
            'pagesize.integer' => BaseConst::$USER_ERROR_PAGE_SIZE_NOT_INT,
            'pagesize.min' => BaseConst::$USER_ERROR_PAGE_SIZE_MIN,
            'pagenum.required' => BaseConst::$USER_ERROR_NO_PAGE_NUM,
            'pagenum.integer' => BaseConst::$USER_ERROR_PAGE_NUM_NOT_INT,
            'pagenum.min' => BaseConst::$USER_ERROR_PAGE_NUM_MIN,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first(), []);
        }
        $data = $managerService->getAdminUserList($param);
        return $this->jsonReturnElse($data);
    }

    /**
     * @auther zlq
     * @create_time 2020/7/13 17:59
     * @description 新增管理员用户
     * @param Request $request
     * @param ManagerService $managerService
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAdminUser(Request $request, ManagerService $managerService)
    {
        $param = $request->all(['username', 'password', 'email', 'mobile']);
        // 验证数据
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:3|max:15',
            'password' => 'required|min:3|max:15',
            'email' => 'required|email|unique:users'
        ], [
            'username.required' => BaseConst::$ADD_USER_ERROR_NO_USERNAME,
            'username.max' => BaseConst::$ADD_USER_ERROR_USERNAME_MIN_AND_MAX,
            'username.min' => BaseConst::$ADD_USER_ERROR_USERNAME_MIN_AND_MAX,
            'password.required' => BaseConst::$ADD_USER_ERROR_NO_PASSWORD,
            'password.max' => BaseConst::$ADD_USER_ERROR_PASSWORD_MIN_AND_MAX,
            'password.min' => BaseConst::$ADD_USER_ERROR_PASSWORD_MIN_AND_MAX,
            'email.required' => BaseConst::$ADD_USER_ERROR_NO_EMAIL,
            'email.email' => BaseConst::$ADD_USER_ERROR_EMAIL_FORMAT,
            'email.unique' => BaseConst::$ADD_USER_ERROR_EMAIL_UNIQUE,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first(), []);
        }
        $data = $managerService->addAdminUser($param);
        return $this->jsonReturnElse($data);
    }

    public function changeAdminUserState($uid, $type, ManagerService $managerService)
    {
        $param = ['uid' => $uid, 'type' => (boolean)$type];
        // 验证数据
        $validator = Validator::make($param, [
            'uid' => 'required|integer',
            'type' => 'required|boolean',
        ], [
            'uid.required' => BaseConst::$CHANGE_USER_STATUS_ERROR_NO_UID,
            'uid.integer' => BaseConst::$CHANGE_USER_STATUS_ERROR_UID_FORMAT,
            'type.required' => BaseConst::$CHANGE_USER_STATUS_ERROR_NO_TYPE,
            'type.boolean' => BaseConst::$CHANGE_USER_STATUS_ERROR_TYPE_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first(), []);
        }
        $data = $managerService->changeAdminUserState($param);
        return $this->jsonReturnElse($data);
    }
}
