<?php

namespace App\Http\Controllers\Api;

use App\Service\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ApiConst\BaseConst;

class RoleController extends BaseController
{
    /**
     * @auther zlq
     * @create_time 2020/7/14 16:38
     * @description 获取所有的角色权限列表
     * @param RoleService $roleService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoleList(RoleService $roleService)
    {
        $data = $roleService->getRoleList();
        return $this->jsonReturnElse($data);
    }

    /**
     * @description 添加新的权限
     * @User: zlq
     * @Datetime: 2020/7/14 20:04
     * @param Request $request
     * @param RoleService $roleService
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRole(Request $request, RoleService $roleService)
    {
        $param = $request->all();
        $validator = Validator::make($param, [
            'roleName' => 'required|string|min:2|max:25|unique:sp_role,role_name',
            'roleDesc' => 'required|string|min:2|max:25',
        ],[
            'roleName.required' => BaseConst::$ROLE_ADD_ERROR_NO_AUTH_NAME,
            'roleName.string' => BaseConst::$ROLE_ADD_ERROR_AUTH_NAME_FORMAT,
            'roleName.min' => BaseConst::$ROLE_ADD_ERROR_AUTH_NAME_MIN,
            'roleName.max' => BaseConst::$ROLE_ADD_ERROR_AUTH_NAME_MAX,
            'roleName.unique' => BaseConst::$ROLE_ADD_ERROR_AUTH_NAME_UNIQUE,
            'roleDesc.required' => BaseConst::$ROLE_ADD_ERROR_NO_AUTH_DESC,
            'roleDesc.string' => BaseConst::$ROLE_ADD_ERROR_AUTH_DESC_FORMAT,
            'roleDesc.min' => BaseConst::$ROLE_ADD_ERROR_AUTH_DESC_MIN,
            'roleDesc.max' => BaseConst::$ROLE_ADD_ERROR_AUTH_DESC_MAX,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($roleService->addRole($param));
    }

    /**
     * @description 接受角色ID参数$id,查询角色详情
     * @User: zlq
     * @Datetime: 2020/7/14 21:23
     * @param $id
     * @param RoleService $roleService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoleInfo($id, RoleService $roleService)
    {
        $param['id'] = $id;
        $validator = Validator::make($param, [
            'id' => 'required|integer'
        ], [
            'id.required' => BaseConst::$ROLE_GET_INFO_ERROR_NO_ID,
            'id.integer' => BaseConst::$ROLE_GET_INFO_ERROR_ID_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($roleService->getRoleInfo($param));
    }
}
