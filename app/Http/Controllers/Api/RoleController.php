<?php

namespace App\Http\Controllers\Api;

use App\Service\RoleService;
use Illuminate\Http\Request;

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
}
