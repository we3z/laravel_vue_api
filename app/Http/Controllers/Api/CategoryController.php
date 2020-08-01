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
            'type.integer' => BaseConst::CATEGORY_GET_LIST_ERROR_TYPE_ERROR,
            'pageNum.integer' => BaseConst::CATEGORY_GET_LIST_ERROR_PAGE_NUM_ERROR,
            'pageSize.integer' => BaseConst::CATEGORY_GET_LIST_ERROR_PAGE_SIZE_ERROR,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($categoryService->getCategoryList($param));
    }
}
