<?php

namespace App\Http\Controllers\Api;

use App\ApiConst\BaseConst;
use App\Http\Controllers\Controller;
use App\Service\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends BaseController
{
    // 获取左侧菜单列表
    public function getLeftMenu(PermissionService $permissionService)
    {
        $data = $permissionService->getLeftMenu();
        return $this->jsonReturnElse($data);
    }

    /**
     * @auther zlq
     * @create_time 2020/7/14 15:52
     * @description 获取权限列表
     * @param $type 获取风格 list 列表 tree 树
     * @param PermissionService $permissionService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissionList($type, PermissionService $permissionService)
    {
        $param = ['type' => $type];
        // 验证数据
        $validator = Validator::make($param, [
            'type' => 'required|string'
        ], [
            'type.required' => BaseConst::$PERMISSION_ERROR_NO_TYPE,
            'type.string' => BaseConst::$PERMISSION_ERROR_TYPE_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        if (($param['type'] !== 'tree')  && ($param['type'] !== 'list')) {
            return $this->jsonReturn(BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, BaseConst::$PERMISSION_ERROR_TYPE_USELESS);
        }
        $data = $permissionService->getPermissionList($param);
        return $this->jsonReturnElse($data);
    }
}
