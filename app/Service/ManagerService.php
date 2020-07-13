<?php
/**
 * @auther zlq
 * @create_time 2020/7/10 17:58
 * @description
 */


namespace App\Service;
use App\ApiConst\BaseConst;
use Carbon\Carbon;
use App\Model\User;
use Illuminate\Support\Facades\Auth;


class ManagerService
{
    public function loginAndGetToken()
    {
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)) {
            return ['code' => BaseConst::$HTTP_ERROR_UNAUTHORIZED_CODE, 'msg' => BaseConst::$LOGIN_ERROR_MSG, 'data' => ''];
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
        return ['code' => BaseConst::$HTTP_SUCCESS_CODE, 'msg' => BaseConst::$LOGIN_SUCCESS_MSG, 'data' => $data];
    }
}
