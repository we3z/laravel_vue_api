<?php
namespace App\Service;

use App\ApiConst\BaseConst;
use App\Model\Permission;

class PermissionService
{
    protected $permissionModel;

    public function __construct()
    {
        $this->permissionModel = new Permission();
    }

    /**
     * @auther zlq
     * @create_time 2020/7/13 14:49
     * @description 获取后台管理左侧菜单
     * @return array
     */
    public function getLeftMenu()
    {
        $list = $this->permissionModel::with(['connectPermissionApi'=>function($query){
            $query->select('ps_id','ps_api_path');
        }])->where('ps_level', 1)->orWhere('ps_level', 3)->get()->toArray();
        if (empty($list)) {
            return ['code' => BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::$PERMISSION_ERROR_NO_MENU_DATA, 'data' => []];
        }
        // 处理1,2级菜单
        $box = [];
        $parent_box = [];
        foreach ($list as $item) {
            $temp = [
                'id' => $item['ps_id'],
                'authName' => $item['ps_name'],
                'path' => $item['connect_permission_api']['ps_api_path'],
                'children' => []
            ];
            if ($item['ps_level'] == '0') {
                $parent_box[] = $temp;
            } else {
                $box[$item['ps_pid']][] = $temp;
            }
        }
        foreach ($parent_box as $key => $val) {
            if (isset($box[$val['id']])) {
                $parent_box[$key]['children'] = $box[$val['id']];
            }
        }
        return ['code' => BaseConst::$HTTP_SUCCESS_CODE, 'msg' => BaseConst::$PERMISSION_SUCCESS_GET_MENU_DATA, 'data' => $parent_box];
    }

    /**
     * @auther zlq
     * @create_time 2020/7/14 15:51
     * @description 获取权限列表
     * @param $param
     * @return array
     */
    public function getPermissionList($param)
    {
        $list = $this->permissionModel::with(['connectPermissionApi'=>function($query){
            $query->select('ps_id','ps_api_path');
        }])->get()->toArray();
        if (empty($list)) {
            return ['code' => BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::$PERMISSION_ERROR_RESULT, 'data' => []];
        }
        $box = [];
        foreach ($list as $item) {
            $box[] = [
                'id' => $item['ps_id'],
                'authName' => $item['ps_name'],
                'path' => $item['connect_permission_api']['ps_api_path'],
                'level' => $item['ps_level'],
                'pid' =>$item['ps_pid']
            ];
        }
        switch ($param['type']) {
            case 'list' :
                return ['code' => BaseConst::$HTTP_SUCCESS_CODE, 'msg' => BaseConst::$PERMISSION_SUCCESS_RESULT, 'data' => $box];
                break;
            case 'tree':
                $new = format_data_tree($box);
                return ['code' => BaseConst::$HTTP_SUCCESS_CODE, 'msg' => BaseConst::$PERMISSION_SUCCESS_RESULT, 'data' => $new];
                break;
            default:
                return ['code' => BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::$PERMISSION_ERROR_RESULT, 'data' => []];
        }
    }
}
