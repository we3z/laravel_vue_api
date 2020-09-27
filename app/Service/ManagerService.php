<?php
/**
 * @auther zlq
 * @create_time 2020/7/10 17:58
 * @description
 */


namespace App\Service;
use App\ApiConst\BaseConst;
use App\Models\Role;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManagerService
{
    /**
     * @auther zlq
     * @create_time 2020/7/13 16:04
     * @description 登录获取token
     * @return array
     */
    public function loginAndGetToken()
    {
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)) {
            return ['code' => BaseConst::HTTP_ERROR_UNAUTHORIZED_CODE, 'msg' => BaseConst::LOGIN_ERROR_MSG, 'data' => ''];
        }
        $user = request()->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if (request()->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        $data = $user;
        $data['token'] = 'Bearer '. $tokenResult->accessToken;
        return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::LOGIN_SUCCESS_MSG, 'data' => $data];
    }

    /**
     * @auther zlq
     * @create_time 2020/7/13 17:42
     * @description 获取后台管理用户列表
     * @param $param
     * @return array
     */
    public function getAdminUserList($param)
    {
        $where = [];
        if (isset($param['query']) && $param['query']) {
            $where[] = ['name', 'like', '%' . $param['query'] . '%'];
        }
        $data = User::query()
            ->with(['connectRole' => function($query) {
                $query->select('role_id', 'role_name');
            }])
            ->where($where)->paginate($param['pagesize'], ['id', 'name', 'type', 'email', 'mobile', 'created_at', 'status', 'role_id'], 'page', $param['pagenum']);

        $users = $data->items();
        $response = ['totalpage' => $data->total(), 'pagenum' => $param['pagenum'], 'users' => []];
        if (!empty($users))  {
            $user_list = [];
            foreach ($users as $key => $item) {
                $temp = $item->toArray();
                $user_list[] = [
                    'id' => $temp['id'],
                    'username' => $temp['name'],
                    'mobile' => $temp['mobile'],
                    'type' => $temp['type'],
                    'email' => $temp['email'],
                    'create_time' => $temp['created_at'],
                    'mg_state' => $temp['status'] ? true : false,
                    'role_name' => $temp['connect_role']['role_name'],
                ];
            }
            $response['users'] = $user_list;
        }
        return [
            'code' => BaseConst::HTTP_SUCCESS_CODE,
            'msg' => BaseConst::USER_SUCCESS_GET_DATA,
            'data' => $response
        ];
    }

    /**
     * @auther zlq
     * @create_time 2020/7/13 17:43
     * @description 新增用户
     * @param $param
     */
    public function addAdminUser($param)
    {
        $user = new User([
            'name' => $param['username'],
            'email' => $param['email'],
            'password' => bcrypt($param['password']),
            'mobile' => $param['mobile']
        ]);
        $result = $user->save();
        if ($result) {
            $userData = $user->toArray();
            return ['code' => BaseConst::HTTP_SUCCESS_CREATE_CODE, 'msg' => BaseConst::ADD_USER_SUCCESS_RESULT, 'data' => $userData];
        } else {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ADD_USER_ERROR_RESULT, 'data' => []];
        }
    }

    /**
     * @auther zlq
     * @create_time 2020/7/13 18:27
     * @description 修改管理员用户状态
     * @param $param
     * @return array
     */
    public function changeAdminUserState($param)
    {
        $userData = User::query()->find($param['uid'])->toArray();
        if (empty($userData)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CHANGE_USER_STATUS_ERROR_USER_DATA, 'data' => []];
        }
        $status = $param['type'] ? 1 : 0;
        if ($userData['status'] !== $status) {
            $result =  User::query()->where('id', $param['uid'])->update(['status' => $status]);
            if (!$result) {
                return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CHANGE_USER_STATUS_ERROR_RESULT, 'data' => []];
            }
        }
        return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::CHANGE_USER_STATUS_SUCCESS_RESULT, 'data' => []];
    }

    /**
     * @auther zlq
     * @create_time 2020/7/14 9:57
     * @description 获取管理用户信息
     * @param $param
     * @return array
     */
    public function getAdminUserInfo($param)
    {
        $userData = User::query()->find(($param['id']))->toArray();
        if (empty($userData)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::GET_USER_INFO_ERROR_RESULT, 'data' => []];
        }
        $userInfo = [
            'id' => $userData['id'],
            'username' => $userData['name'],
            'role_id' => $userData['role_id'],
            'mobile' => $userData['mobile'],
            'email' => $userData['email'],
        ];
        return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::GET_USER_INFO_SUCCESS_RESULT, 'data' =>$userInfo];
    }

    /**
     * @auther zlq
     * @create_time 2020/7/14 14:47
     * @description 编辑用户信息
     * @param $param
     * @return array
     */
    public function editAdminUserInfo($param)
    {
        if (!$param['email'] && !$param['mobile'] ) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::EDIT_USER_INFO_ERROR_RESULT, 'data' => []];
        }
        $userData = User::query()->find(($param['id']))->toArray();
        if (empty($userData)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::EDIT_USER_INFO_ERROR_RESULT, 'data' => []];
        }
        if (($param['email'] == $userData['email']) && ($param['mobile'] == $userData['mobile'])) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::EDIT_USER_INFO_ERROR_RESULT, 'data' => []];
        }
        $result = User::query()->where('id', $param['id'])->update(['email' => $param['email'], 'mobile' => $param['mobile']]);
        if (!$result) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::EDIT_USER_INFO_ERROR_RESULT, 'data' => []];
        }
        $userData = User::query()->find(($param['id']))->toArray();
        $userInfo = [
            'id' => $userData['id'],
            'username' => $userData['name'],
            'role_id' => $userData['role_id'],
            'mobile' => $userData['mobile'],
            'email' => $userData['email'],
        ];
        return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::EDIT_USER_INFO_SUCCESS_RESULT, 'data' => $userInfo];
    }

    /**
     * @auther zlq
     * @create_time 2020/7/14 15:00
     * @description 删除用户
     * @param $param
     * @return array
     */
    public function deleteAdminUser($param)
    {
        $userData = User::query()->find($param['id'])->toArray();
        if (empty($userData)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::DELETE_USER_ERROR_RESULT, 'data' => []];
        }
        $result = User::query()->where('id', $param['id'])->delete();
        if (!$result) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::DELETE_USER_ERROR_RESULT, 'data' => []];
        }
        return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::DELETE_USER_SUCCESS_RESULT, 'data' => []];
    }

    /**
     * @auther zlq
     * @create_time 2020/8/1 14:40
     * @description 用户分配权限
     * @param $param
     * @return array
     */
    public function allowAdminUserRole($param)
    {
        $userId = $param['userId'];
        $roleId = $param['roleId'];
        $userData = User::query()->find($userId)->toArray();
        if (empty($userData)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ALLOW_USER_ROLE_ERROR_NO_USER_INFO, 'data' => []];
        }
        $roleData = Role::query()->find($roleId)->toArray();
        if (empty($roleData)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ALLOW_USER_ROLE_ERROR_NO_ROLE_INFO, 'data' => []];
        }
        $res = User::query()->where('id', $userId)->update(['role_id' => $roleId]);
        if ($res) {
            $data = [
                'id' => $userData['id'],
                'rid' => $roleId,
                'username' => $userData['name'],
                'mobile' => $userData['mobile'],
                'email' => $userData['email'],
            ];
            return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::ALLOW_USER_ROLE_SUCCESS_RESULT, 'data' => $data];
        } else {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::ALLOW_USER_ROLE_ERROR_RESULT, 'data' => []];
        }
    }
}
