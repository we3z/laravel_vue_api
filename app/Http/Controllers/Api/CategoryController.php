<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\ApiConst\BaseConst;
use App\Service\CategoryService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends BaseController
{
    /**
     * @author zlq
     * @create_time 2020/10/13 10:30
     * @descrption 获取分类列表
     * @param Request $request
     * @param CategoryService $categoryService
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @author zlq
     * @create_time 2020/10/13 10:31
     * @descrption 添加新的分类
     * @param Request $request
     * @param CategoryService $categoryService
     * @return \Illuminate\Http\JsonResponse
     */
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
            'catLevel.required' => BaseConst::CATEGORY_ADD_ERROR_NO_CAT_LEVEL,
            'catLevel.integer' => BaseConst::CATEGORY_ADD_ERROR_CAT_LEVEL_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($categoryService->addCategory($param));
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 11:03
     * @descrption
     * @param $id int 分类ID
     * @param CategoryService $categoryService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategory($id, CategoryService $categoryService)
    {
        $param = [
            'cat_id' => $id
        ];
        $validator = Validator::make($param, [
            'cat_id' => 'required|integer'
        ],[
            'cat_id.required' => BaseConst::CATEGORY_GET_INFO_ERROR_NO_CAT_ID,
            'cat_id.integer' => BaseConst::CATEGORY_GET_INFO_ERROR_CAT_ID_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($categoryService->getCategoryInfo($param));
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 11:23
     * @descrption 修改分类数据
     * @param $id int 分类ID
     * @param Request $request
     * @param CategoryService $categoryService
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCategory($id, Request $request, CategoryService $categoryService)
    {
        $param = [
            'cat_id' => $id,
            'cat_name' => $request->input('cat_name')
        ];
        $validator = Validator::make($param, [
            'cat_id' => 'required|integer',
            'cat_name' => $request->post('cat_name')
        ],[
            'cat_id.required' => BaseConst::CATEGORY_EDIT_ERROR_NO_CAT_ID,
            'cat_id.integer' => BaseConst::CATEGORY_EDIT_ERROR_CAT_ID_FORMAT,
            'cat_name.required' => BaseConst::CATEGORY_EDIT_NO_CAT_NAME,
            'cat_name.string' => BaseConst::CATEGORY_EDIT_CAT_NAME_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($categoryService->editCategory($param));
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 11:30
     * @descrption
     * @param $id int 分类ID
     * @param CategoryService $categoryService
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCategory($id, CategoryService $categoryService)
    {
        $param = [
            'cat_id' => $id
        ];
        $validator = Validator::make($param, [
            'cat_id' => 'required|integer'
        ],[
            'cat_id.required' => BaseConst::CATEGORY_DELETE_ERROR_NO_CAT_ID,
            'cat_id.integer' => BaseConst::CATEGORY_DELETE_ERROR_CAT_ID_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($categoryService->deleteCategory($param));
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 13:53
     * @descrption 获取某分类下的属性列表
     * @param $id int 分类ID
     * @param Request $request
     * @param CategoryService $categoryService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoryAttr($id, Request $request, CategoryService $categoryService)
    {
        $param = [
            'cat_id' => $id,
            'sel' => $request->input('sel')
        ];
        $validator = Validator::make($param, [
            'cat_id' => 'required|integer',
            'sel' => [
                'required',
                Rule::in(['only', 'many'])
            ]
        ],[
            'cat_id.required' => BaseConst::CATEGORY_GET_ATTR_ERROR_NO_CAT_ID,
            'cat_id.integer' => BaseConst::CATEGORY_GET_ATTR_ERROR_CAT_ID_FORMAT,
            'sel.required' => BaseConst::CATEGORY_GET_ATTR_ERROR_NO_SEL,
            'sel.in' => BaseConst::CATEGORY_GET_ATTR_ERROR_SEL_FORMAT
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($categoryService->getCategoryAttr($param));
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 14:31
     * @descrption 添加分类属性
     * @param $id int 分类ID
     * @param Request $request
     * @param CategoryService $categoryService
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCategoryAttr($id, Request $request, CategoryService $categoryService)
    {
        $param = [
            'cat_id' => $id,
            'attr_name' => $request->input('attr_name'),
            'attr_sel' => $request->input('sel'),
            'attr_vals' => $request->input('attr_vals'),
        ];
        $validator = Validator::make($param, [
            'cat_id' => 'required|integer',
            'attr_name' => 'required|string',
            'attr_sel' => [
                'required',
                Rule::in(['only', 'many'])
            ]
        ],[
            'cat_id.required' => BaseConst::CATEGORY_ADD_ATTR_ERROR_NO_CAT_ID,
            'cat_id.integer' => BaseConst::CATEGORY_ADD_ATTR_ERROR_CAT_ID_FORMAT,
            'attr_name.required' => BaseConst::CATEGORY_ADD_ATTR_ERROR_NO_ATTR_NAME,
            'attr_name.string' => BaseConst::CATEGORY_ADD_ATTR_ERROR_ATTR_NAME_FORMAT,
            'attr_sel.required' => BaseConst::CATEGORY_ADD_ATTR_ERROR_NO_SEL,
            'attr_sel.in' => BaseConst::CATEGORY_ADD_ATTR_ERROR_SEL_FORMAT
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        // 如果为多选时,验证attr_vals
        if ($param['attr_sel'] == 'many') {
            $attrVals = trim($param['attr_vals'], ',');
            if (empty($attrVals)) {
                return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, BaseConst::CATEGORY_ADD_ATTR_ERROR_VALS_FORMAT);
            }
        }
        return $this->jsonReturnElse($categoryService->addCategoryAttr($param));
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 14:44
     * @descrption 删除分类下某属性
     * @param $id int 分类ID
     * @param $attrid int 属性ID
     * @param CategoryService $categoryService
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCategoryAttr($id, $attrid, CategoryService $categoryService)
    {
        $param = [
            'cat_id' => $id,
            'attr_id' => $attrid,
        ];
        $validator = Validator::make($param, [
            'cat_id' => 'required|integer',
            'attr_id' => 'required|integer',

        ],[
            'cat_id.required' => BaseConst::CATEGORY_ATTR_DELETE_ERROR_NO_CAT_ID,
            'cat_id.integer' => BaseConst::CATEGORY_ATTR_DELETE_ERROR_CAT_ID_FORMAT,
            'attr_id.required' => BaseConst::CATEGORY_ATTR_DELETE_ERROR_NO_ATTR_ID,
            'attr_id.string' => BaseConst::CATEGORY_ATTR_DELETE_ERROR_ATTR_ID_FORMAT,
        ]);
        if ($validator->fails()) {
            return $this->jsonReturn(BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, $validator->errors()->first());
        }
        return $this->jsonReturnElse($categoryService->deleteCategoryAttr($param));
    }
}
