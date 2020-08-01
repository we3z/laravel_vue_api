<?php

namespace App\Http\Controllers\Api;

use App\Model\Role;
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
        return $this->jsonReturnElse($roleService->getRoleList());
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
            'roleName.required' => BaseConst::ROLE_ADD_ERROR_NO_AUTH_NAME,
            'roleName.string' => BaseConst::ROLE_ADD_ERROR_AUTH_NAME_FORMAT,
            'roleName.min' => BaseConst::ROLE_ADD_ERROR_AUTH_NAME_MIN,
            'roleName.max' => BaseConst::ROLE_ADD_ERROR_AUTH_NAME_MAX,
            'roleName.unique' => BaseConst::ROLE_ADD_ERROR_AUTH_NAME_UNIQUE,
            'roleDesc.required' => BaseConst::ROLE_ADD_ERROR_NO_AUTH_DESC,
            'roleDesc.string' => BaseConst::ROLE_ADD_ERROR_AUTH_DESC_FORMAT,
            'roleDesc.min' => BaseConst::ROLE_ADD_ERROR_AUTH_DESC_MIN,
            'roleDesc.max' => BaseConst::ROLE_ADD_ERROR_AUTH_DESC_MAX,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
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
            'id.required' => BaseConst::ROLE_GET_INFO_ERROR_NO_ID,
            'id.integer' => BaseConst::ROLE_GET_INFO_ERROR_ID_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($roleService->getRoleInfo($param));
    }

    /**
     * @auther zlq
     * @create_time 2020/7/29 16:01
     * @description 修改权限信息
     * @param $id 权限ID
     * @param Request $request
     * @param RoleService $roleService
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRole($id, Request $request, RoleService $roleService)
    {
        $param['id'] = $id;
        $param['roleName'] = $request->input('roleName');
        $param['roleDesc'] = $request->input('roleDesc');
        $validator = Validator::make($param, [
            'id' => 'required|integer',
            'roleName' => 'required|string',
            'roleDesc' => 'string'
        ], [
            'id.required' => BaseConst::ROLE_EDIT_ERROR_NO_ID,
            'id.integer' => BaseConst::ROLE_EDIT_ERROR_ID_FORMAT,
            'roleName.required' => BaseConst::ROLE_EDIT_ERROR_NO_NAME,
            'roleName.string' => BaseConst::ROLE_EDIT_ERROR_NAME_FORMAT,
            'roleDesc.string' => BaseConst::ROLE_EDIT_ERROR_DESC_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($roleService->updateRole($param));
    }

    /**
     * @auther zlq
     * @create_time 2020/7/29 16:34
     * @description 删除角色
     * @param $id 角色ID
     * @param RoleService $roleService
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRole($id, RoleService $roleService)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer'
        ], [
            'id.required' => BaseConst::ROLE_DELETE_ERROR_NO_ID,
            'id.integer' => BaseConst::ROLE_DELETE_ERROR_ID_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($roleService->deleteRole($id));
    }

    /**
     * @auther zlq
     * @create_time 2020/8/1 10:59
     * @description 删除角色权限
     * @param $roleId 角色ID
     * @param $rightId 权限ID
     * @param RoleService $roleService
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRightOfRole($roleId, $rightId, RoleService $roleService)
    {
        $param = [ 'roleId' => $roleId, 'rightId' => $rightId ];
        $validator = Validator::make($param, [
            'roleId' => 'required|integer',
            'rightId' => 'required|integer',
        ], [
            'roleId.required' => BaseConst::ROLE_DELETE_RIGHT_ERROR_NO_ROLE_ID,
            'roleId.integer' => BaseConst::ROLE_DELETE_RIGHT_ERROR_ROLE_ID_FORMAT,
            'rightId.required' => BaseConst::ROLE_DELETE_RIGHT_ERROR_NO_RIGHT_ID,
            'rightId.integer' => BaseConst::ROLE_DELETE_RIGHT_ERROR_RIGHT_ID_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($roleService->deleteRightOfRole($param));
    }

    /**
     * @auther zlq
     * @create_time 2020/8/1 15:55
     * @description 角色分配权限
     * @param $roleId 角色ID
     * @param Request $request
     * @param RoleService $roleService
     * @return \Illuminate\Http\JsonResponse
     */
    public function allowRightToRole($roleId, Request $request, RoleService $roleService)
    {
        $rids = $request->post('rids');
        $rids = trim(trim($rids), ',');
        $param = [ 'roleId' => $roleId,  'rids' => $rids ];
        $validator = Validator::make($param, [
            'roleId' => 'required|integer',
            'rids' => 'required|string',
        ], [
            'roleId.required' => BaseConst::ROLE_ALLOW_RIGHT_ERROR_NO_ROLE_ID,
            'roleId.integer' => BaseConst::ROLE_ALLOW_RIGHT_ERROR_ROLE_ID_FORMAT,
            'rids.required' => BaseConst::ROLE_ALLOW_RIGHT_ERROR_NO_RIGHT_IDS,
            'rids.string' => BaseConst::ROLE_ALLOW_RIGHT_ERROR_RIGHT_IDS_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($roleService->allowRightToRole($param));
    }
}
