<?php
/**
 * @auther zlq
 * @create_time 2020/7/14 16:03
 * @description
 */


namespace App\Service;


use App\ApiConst\BaseConst;
use App\Model\Permission;
use App\Model\Role;

class RoleService
{
    /**
     * @auther zlq
     * @create_time 2020/7/14 16:38
     * @description 获取所有的权限角色列表
     * @return array
     */
    public function getRoleList()
    {
        $roleList = Role::query()
            ->select('role_id as id', 'role_name as roleName', 'role_desc as roleDesc', 'ps_ids')
            ->get()
            ->toArray();
        if (empty($roleList)) {
            return ['code' => BaseConst::$HTTP_SUCCESS_CODE, 'msg' => BaseConst::$ROLE_SUCCESS_RESULT, 'data' => []];
        }
        $all = [];
        foreach ($roleList as $item) {
            $ids = $item['ps_ids'];
            unset($item['ps_ids']);
            $item['children'] = [];
            if ($ids) {
                $roles = Permission::query()->leftJoin('sp_permission_api', 'sp_permission_api.ps_id', '=', 'sp_permission.ps_id')
                    ->whereIn('sp_permission.ps_id', explode(',', $ids))
                    ->select('sp_permission.ps_id as id', 'sp_permission.ps_name as authName', 'sp_permission.ps_pid as pid', 'sp_permission.ps_level as level', 'sp_permission_api.ps_api_path as path')
                    ->get()
                    ->toArray();
                if (!empty($ids)) {
                    $item['children'] = format_data_tree($roles);
                }
            }
            $all[] = $item;
        }
        return ['code' => BaseConst::$HTTP_SUCCESS_CODE, 'msg' => BaseConst::$ROLE_SUCCESS_RESULT, 'data' => $all];
    }
}
