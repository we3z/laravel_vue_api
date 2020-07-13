<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ApiConst\BaseConst;

class BaseController extends Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Method:POST,GET,DELETE,PUT,PATCH');//允许访问的方式
    }

    /**
     * @auther zlq
     * @create_time 2020/7/13 8:49
     * @description json格式返回数据
     * @param $code 返回状态
     * @param $msg 返回信息
     * @param array $data 返回数据
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonReturn($code, $msg, $data = [])
    {
        $response = [
            'meta' => [
                'status' => $code,
                'msg' => $msg
            ],
            'data' => $data,
        ];
        return response()->json($response);
    }

    /**
     * @auther zlq
     * @create_time 2020/7/13 14:56
     * @description json格式返回数据
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonReturnElse($data = [])
    {
        $response = [
            'meta' => [
                'status' => $data['code'],
                'msg' => $data['msg']
            ],
            'data' => $data['data'],
        ];
        return response()->json($response);
    }
}
