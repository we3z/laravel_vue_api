<?php

namespace App\Http\Controllers\Api;

use App\ApiConst\BaseConst;
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
            'pagesize.required' => BaseConst::USER_ERROR_NO_PAGE_SIZE,
            'pagesize.integer' => BaseConst::USER_ERROR_PAGE_SIZE_NOT_INT,
            'pagesize.min' => BaseConst::USER_ERROR_PAGE_SIZE_MIN,
            'pagenum.required' => BaseConst::USER_ERROR_NO_PAGE_NUM,
            'pagenum.integer' => BaseConst::USER_ERROR_PAGE_NUM_NOT_INT,
            'pagenum.min' => BaseConst::USER_ERROR_PAGE_NUM_MIN,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first(), []);
        }
        return $this->jsonReturnElse($managerService->getAdminUserList($param));
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
            'username.required' => BaseConst::ADD_USER_ERROR_NO_USERNAME,
            'username.max' => BaseConst::ADD_USER_ERROR_USERNAME_MIN_AND_MAX,
            'username.min' => BaseConst::ADD_USER_ERROR_USERNAME_MIN_AND_MAX,
            'password.required' => BaseConst::ADD_USER_ERROR_NO_PASSWORD,
            'password.max' => BaseConst::ADD_USER_ERROR_PASSWORD_MIN_AND_MAX,
            'password.min' => BaseConst::ADD_USER_ERROR_PASSWORD_MIN_AND_MAX,
            'email.required' => BaseConst::ADD_USER_ERROR_NO_EMAIL,
            'email.email' => BaseConst::ADD_USER_ERROR_EMAIL_FORMAT,
            'email.unique' => BaseConst::ADD_USER_ERROR_EMAIL_UNIQUE,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first(), []);
        }
        return $this->jsonReturnElse($managerService->addAdminUser($param));
    }

    /**
     * @auther zlq
     * @create_time 2020/7/14 9:47
     * @description 修改管理员用户状态
     * @param $uid 用户状态
     * @param $type 修改类型
     * @param ManagerService $managerService
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeAdminUserState($uid, $type, ManagerService $managerService)
    {
        $param = ['uid' => $uid, 'type' => (boolean)$type];
        // 验证数据
        $validator = Validator::make($param, [
            'uid' => 'required|integer',
            'type' => 'required|boolean',
        ], [
            'uid.required' => BaseConst::CHANGE_USER_STATUS_ERROR_NO_UID,
            'uid.integer' => BaseConst::CHANGE_USER_STATUS_ERROR_UID_FORMAT,
            'type.required' => BaseConst::CHANGE_USER_STATUS_ERROR_NO_TYPE,
            'type.boolean' => BaseConst::CHANGE_USER_STATUS_ERROR_TYPE_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first(), []);
        }
        return $this->jsonReturnElse($managerService->changeAdminUserState($param));
    }

    /**
     * @auther zlq
     * @create_time 2020/7/14 9:57
     * @description 获取管理用户信息
     * @param $id
     * @param ManagerService $managerService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminUserInfo($id, ManagerService $managerService)
    {
        $param = ['id' => $id];
        // 验证数据
        $validator = Validator::make($param, [
           'id' => 'required|integer'
        ], [
            'id.required' => BaseConst::GET_USER_INFO_ERROR_NO_ID,
            'id.integer' => BaseConst::GET_USER_INFO_ERROR_ID_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($managerService->getAdminUserInfo($param));
    }

    /**
     * @auther zlq
     * @create_time 2020/7/14 14:48
     * @description 编辑用户信息
     * @param $id 用户ID
     * @param Request $request
     * @param ManagerService $managerService
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAdminUserInfo($id, Request $request, ManagerService $managerService)
    {
        $param = [
            'id' => $id,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ];
        // 验证数据
        $validator = Validator::make($param, [
            'id' => 'required|integer',
            'email' => 'required|email',
            'mobile' => 'required',
        ], [
            'id.required' => BaseConst::EDIT_USER_INFO_ERROR_NO_ID,
            'id.integer' => BaseConst::EDIT_USER_INFO_ERROR_ID_FORMAT,
            'email.required' => BaseConst::EDIT_USER_INFO_ERROR_NO_EMAIL,
            'email.email' => BaseConst::EDIT_USER_INFO_ERROR_EMAIL_FORMAT,
             // 'email.unique' => BaseConst::EDIT_USER_INFO_ERROR_EMAIL_UNIQUE,
            'mobile.required' => BaseConst::EDIT_USER_INFO_ERROR_NO_MOBILE,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($managerService->editAdminUserInfo($param));
    }

    /**
     * @auther zlq
     * @create_time 2020/7/14 15:00
     * @description 删除用户
     * @param $id 用户ID
     * @param ManagerService $managerService
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAdminUser($id, ManagerService $managerService)
    {
        $param = ['id' => $id];
        // 验证数据
        $validator = Validator::make($param, [
            'id' => 'required|integer'
        ], [
            'id.required' => BaseConst::DELETE_USER_ERROR_NO_ID,
            'id.integer' => BaseConst::DELETE_USER_ERROR_ID_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($managerService->deleteAdminUser($param));
    }

    /**
     * @auther zlq
     * @create_time 2020/8/1 15:56
     * @description 用户分配角色
     * @param $id 用户ID
     * @param Request $request
     * @param ManagerService $managerService
     * @return \Illuminate\Http\JsonResponse
     */
    public function allowAdminUserRole($id, Request $request, ManagerService $managerService)
    {
        $roleId = $request->input('rid');
        $param = [ 'userId' => $id, 'roleId' => $roleId ];
        // 验证数据
        $validator = Validator::make($param, [
            'userId' => 'required|integer',
            'roleId' => 'required|integer',
        ], [
            'userId.required' => BaseConst::ALLOW_USER_ROLE_ERROR_NO_USER_ID,
            'userId.integer' => BaseConst::ALLOW_USER_ROLE_ERROR_USER_ID_FORMAT,
            'roleId.required' => BaseConst::ALLOW_USER_ROLE_ERROR_NO_ROLE_ID,
            'roleId.integer' => BaseConst::ALLOW_USER_ROLE_ERROR_ROLE_ID_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($managerService->allowAdminUserRole($param));
    }
}
