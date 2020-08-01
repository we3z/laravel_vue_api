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
            ->select('role_id as id', 'role_id as roleId' ,'role_name as roleName', 'role_desc as roleDesc', 'ps_ids')
            ->get()
            ->toArray();
        if (empty($roleList)) {
            return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::ROLE_SUCCESS_RESULT, 'data' => []];
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
        return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::ROLE_SUCCESS_RESULT, 'data' => $all];
    }

    /**
     * @description 添加新的权限
     * @User: zlq
     * @Datetime: 2020/7/14 20:04
     * @param $param
     * @return array
     */
    public function addRole($param)
    {
        $role = new Role([
            'role_name' => $param['roleName'],
            'role_desc' => $param['roleDesc']
        ]);
        $result = $role->save();
        if ($result) {
            $data = $role->toArray();
            return ['code' => BaseConst::HTTP_SUCCESS_CREATE_CODE, 'msg' =>BaseConst::ROLE_ADD_SUCCESS_RESULT,  'data' => $data];
        }
         return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' =>BaseConst::ROLE_ADD_ERROR_RESULT,  'data' => []];
    }

    /**
     * @description 根据角色ID查询角色详情
     * @User: zlq
     * @Datetime: 2020/7/14 21:21
     * @param $param
     * @return array
     */
    public function getRoleInfo($param)
    {
        $role = Role::query()
            ->select('role_id as roleId', 'role_name as roleName', 'role_desc as roleDesc')
            ->find($param['id'])->toArray();
        if (empty($role)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_GET_INFO_ERROR_RESULT, 'data' => []];
        }
        return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::ROLE_GET_INFO_SUCCESS_RESULT, 'data' => $role];
    }

    /**
     * @auther zlq
     * @create_time 2020/7/29 16:00
     * @description 修改权限信息
     * @param $param 参数
     * @return array 返回数据
     */
    public function updateRole($param)
    {
        $role = Role::query()
            ->find($param['id']);
        if (empty($role)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_EDIT_ERROR_NO_ROLE, 'data' => []];
        }
        $res = Role::query()
            ->where('role_id', $param['id'])
            ->update([
                'role_name' => $param['roleName'],
                'role_desc' => $param['roleDesc']
            ]);
        if ($res) {
            $param['roleId'] = $param['id'];
            return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::ROLE_EDIT_SUCCESS_RESULT, 'data' => $param];
        } else {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_EDIT_ERROR_RESULT, 'data' => []];
        }
    }

    /**
     * @auther zlq
     * @create_time 2020/7/29 16:34
     * @description 删除角色
     * @param int $id 角色ID
     * @return array
     */
    public function deleteRole(int $id)
    {
        $role = Role::query()->find($id);
        if (empty($role)) {
            return  ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::ROLE_DELETE_SUCCESS_RESULT, 'data' => []];
        }
        $res = Role::query()
            ->where('role_id', $id)
            ->delete();
        if ($res) {
            return  ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::ROLE_DELETE_SUCCESS_RESULT, 'data' => []];
        } else {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_DELETE_ERROR_RESULT, 'data' => []];
        }
    }

    /**
     * @auther zlq
     * @create_time 2020/8/1 11:18
     * @description 删除角色的部分权限
     * @param $param
     * @return array
     */
    public function deleteRightOfRole($param)
    {
        $roleId = $param['roleId'];
        $rightId = $param['rightId'];
        // 查询角色是否存在
        $roleInfo = Role::query()->find($roleId);
        if (empty($roleInfo)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_DELETE_RIGHT_ERROR_NO_ROLE_INFO, 'data' => []];
        }
        $rightIfo = Permission::query()->find($rightId);
        if (empty($rightIfo)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_DELETE_RIGHT_ERROR_NO_RIGHT_INFO, 'data' => []];
        }
        // 判断该角色是否拥有该权限
        $roleRightArray = array_filter(explode(',', $roleInfo['ps_ids']));
        if (empty($roleRightArray)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_DELETE_RIGHT_ERROR_ROLE_NO_RIGHT, 'data' => []];
        }
        if (!in_array($rightId, $roleRightArray)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_DELETE_RIGHT_ERROR_ROLE_NOT_HAVE_RIGHT, 'data' => []];
        }
        $box = [ $rightId ];
        switch ($roleInfo['ps_level']) {
            case 0:
                // 查询1级菜单
                $second = Permission::query()
                    ->where(['ps_pid' => $rightId, 'ps_level' => 1])
                    ->select('ps_id', 'ps_pid', 'ps_level')
                    ->get()
                    ->toArray();
                if (!empty($second)) {
                    foreach ($second as $aoo) {
                        $box[] = $aoo['ps_id'];
                        // 查询2级ID
                        $third = Permission::query()
                            ->where(['ps_pid' => $aoo['ps_id'], 'ps_level' => 2])
                            ->select('ps_id', 'ps_pid', 'ps_level')
                            ->get()
                            ->toArray();
                        if (empty($third)) {
                            continue;
                        }
                        foreach ($third as $boo) {
                            $box[] = $boo['ps_id'];
                        }
                    }
                }
                break;
            case 1:
                // 查询2级菜单
                $third = Permission::query()
                    ->where(['ps_pid' => $rightId, 'ps_level' => 2])
                    ->select('ps_id', 'ps_pid', 'ps_level')
                    ->get()
                    ->toArray();
                if (!empty($third)) {
                    foreach ($third as $aoo) {
                        $box[] = $aoo['ps_id'];
                    }
                }
                break;
            case 2:
                // 不用做处理
                break;
            default:
                return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_DELETE_RIGHT_ERROR_ROLE_NO_RIGHT, 'data' => []];
        }
        // 当前权限排除$box
        $newRoleRightBox = [];
        foreach ($roleRightArray as $item) {
            // 在box中权限不能再要了
            if (!in_array($item, $box)) {
                $newRoleRightBox[] = $item;
            }
        }
        $newRoleRightBoxString = implode(',', $newRoleRightBox);
        $res = Role::query()
            ->where('role_id', $roleId)
            ->update(['ps_ids' => $newRoleRightBoxString]);
        // 新的权限结构
        $newRoleRight = Permission::query()->leftJoin('sp_permission_api', 'sp_permission_api.ps_id', '=', 'sp_permission.ps_id')
            ->whereIn('sp_permission.ps_id', $newRoleRightBox)
            ->select('sp_permission.ps_id as id', 'sp_permission.ps_name as authName', 'sp_permission.ps_pid as pid', 'sp_permission.ps_level as level', 'sp_permission_api.ps_api_path as path')
            ->get()
            ->toArray();
        // 处理数据结构
        $data = format_data_tree($newRoleRight);
        if ($res) {
            return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::ROLE_DELETE_RIGHT_SUCCESS_RESULT, 'data' => $data];
        } else {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_DELETE_RIGHT_ERROR_RESULT, 'data' => []];
        }
    }

    /**
     * @auther zlq
     * @create_time 2020/8/1 11:18
     * @description 向角色分配权限
     * @param $param
     * @return array
     */
    public function allowRightToRole($param)
    {
        $roleId = $param['roleId'];
        $rids = $param['rids'];
        // 查询角色是否存在
        $roleInfo = Role::query()->find($roleId);
        if (empty($roleInfo)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_ALLOW_RIGHT_ERROR_NO_ROLE_INFO, 'data' => []];
        }
        $rightIdArray = explode(',', $rids);
        if (empty($rightIdArray)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_ALLOW_RIGHT_ERROR_NO_RIGHT_IDS, 'data' => []];
        }
        // 验证是否有不存在的权限
        $rightIdArrayLength = count($rightIdArray);
        $DbRightIdLength = Permission::query()
            ->whereIn('ps_id', $rightIdArray)
            ->count();
        if ($rightIdArrayLength !== $DbRightIdLength) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_ALLOW_RIGHT_ERROR_NO_RIGHT_INFO, 'data' => []];
        }
        $res = Role::query()
            ->where('role_id', $roleId)
            ->update(['ps_ids' => $rids]);
        if ($res) {
            return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::ROLE_ALLOW_RIGHT_SUCCESS_RESULT, 'data' => []];
        } else {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ROLE_ALLOW_RIGHT_ERROR_RESULT, 'data' => []];
        }
    }
}
