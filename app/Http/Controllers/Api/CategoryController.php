<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\ApiConst\BaseConst;
use App\Service\CategoryService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends BaseController
{
    public function getCategoryList(Request $request, CategoryService $categoryService)
    {
        $param['type'] = $request->get('type');
        $param['pageNum'] = $request->get('pageNum');
        $param['pageSize'] = $request->get('pageSize');
        $validator = Validator::make($param, [
            'type' => 'integer',
            'pageNum' => 'integer',
            'pageSize' => 'integer',
        ], [
            'type.integer' => BaseConst::CATEGORY_GET_LIST_ERROR_TYPE_FORMAT,
            'pageNum.integer' => BaseConst::CATEGORY_GET_LIST_ERROR_PAGE_NUM_FORMAT,
            'pageSize.integer' => BaseConst::CATEGORY_GET_LIST_ERROR_PAGE_SIZE_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($categoryService->getCategoryList($param));
    }

    public function addCategory(Request $request, CategoryService $categoryService)
    {
        $param = [
            'catPid' => $request->post('catPid'),
            'catName' => $request->post('catName'),
            'catLevel' => $request->post('catLevel'),
        ];
        $validator = Validator::make($param, [
            'catPid' => 'required|integer',
            'catName' => 'required|string',
            'catLevel' => 'required|integer'
        ],[
            'catPid.required' => BaseConst::CATEGORY_ADD_ERROR_NO_CAT_PID,
            'catPid.integer' => BaseConst::CATEGORY_ADD_ERROR_CAT_PID_FORMAT,
            'catName.required' => BaseConst::CATEGORY_ADD_ERROR_NO_CAT_NAME,
            'catName.string' => BaseConst::CATEGORY_ADD_ERROR_CAT_NAME_FORMAT,
            'catLevel.required' => BaseConst::CATEGORY_ADD_ERROR_NO_CAT_PID,
            'catLevel.integer' => BaseConst::CATEGORY_ADD_ERROR_CAT_LEVEL_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($categoryService->addCategory($param));
    }
}
