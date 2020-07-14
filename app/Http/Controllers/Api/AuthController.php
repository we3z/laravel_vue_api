<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Service\ManagerService;
use Illuminate\Support\Facades\Validator;
use App\ApiConst\BaseConst;
class AuthController extends BaseController
{
    /**
     * @auther zlq
     * @create_time 2020/7/13 11:41
     * @description 用户登录接口
     * @param Request $request
     * @param ManagerService $managerService
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request, ManagerService $managerService)
    {
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Method:POST,GET,DELETE,PUT,PATCH');//允许访问的方式
        // 验证数据
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => BaseConst::$LOGIN_NO_EMAIL_MSG,
            'email.string' => BaseConst::$LOGIN_EMAIL_TYPE_MSG,
            'email.email' => BaseConst::$LOGIN_EMAIL_FORMAT_MSG,
            'password.required' => BaseConst::$LOGIN_NO_PASSWORD_MSG,
            'password.string' => BaseConst::$LOGIN_PASSWORD_TYPE_MSG,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first(), []);
        }
        $data = $managerService->loginAndGetToken();
        return $this->jsonReturn($data['code'], $data['msg'], $data['data']);
    }
}
