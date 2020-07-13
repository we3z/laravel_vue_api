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
<<<<<<< HEAD
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
=======
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['name', 'password']);

        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
>>>>>>> 82bb3c0a3d13fef650188e086a837270ec4bf189
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::$HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first(), []);
        }
        $data = $managerService->loginAndGetToken();
        return $this->jsonReturn($data['code'], $data['msg'], $data['data']);
    }
}
