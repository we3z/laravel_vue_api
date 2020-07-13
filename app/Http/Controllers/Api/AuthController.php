<?php

namespace App\Http\Controllers\Api;

use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Service\ManagerService;
use Illuminate\Support\Facades\Validator;
use App\ApiConst\BaseConst;
class AuthController extends BaseController
{
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request, ManagerService $managerService)
    {
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
