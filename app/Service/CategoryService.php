<?php
namespace App\Service;

use app\ApiConst\BaseConst;
use App\Models\Attribute;
use App\Models\Category;

class CategoryService
{
    /**
     * @auther zlq
     * @create_time 2020/8/3 7:25
     * @description 获取商品分类列表
     * @param $param
     * @return array
     */
    public function getCategoryList($param)
    {
        if (!$param['type']) {
            $param['type'] = 3;
        }
        if (!$param['pageNum']) {
            $param['pageNum'] = 1;
        }
        if (!$param['pageSize']) {
            $param['pageSize'] = 0;
        }
        // 一定是从一级分类查起
        // 有数据条数页码才有意义
        if ($param['pageSize'] > 0) {
            $firstLevel = Category::query()
                ->where('cat_level', '=', 0)
                ->limit($param['pageSize'])
                ->paginate($param['pageSize'], ['cat_id', 'cat_name', 'cat_pid',  'cat_level'], 'page', $param['pageNum']);
            $response = ['totalPage' => $firstLevel->total(), 'pageNum' => $param['pageNum'], 'data' => []];
        } else {
            $firstLevel = Category::query()
                ->where('cat_level', '=', 0)
                ->select('cat_id', 'cat_name', 'cat_pid', 'cat_level')
                ->get();
            $response = ['totalPage' => 1, 'pageNum' => 1, 'data' => []];
        }
        $result = [];
        if (!empty($firstLevel)) {
            switch ($param['type']) {
                case 1:
                    foreach ($firstLevel as $aoo) {
                        $aoo = $aoo->toArray();
                        $aoo['cat_deleted'] = false;
                        $result[] = $aoo;
                    }
                    break;
                case 2:
                    foreach ($firstLevel as $aoo) {
                        $aoo = $aoo->toArray();
                        $secondLevel = Category::query()
                            ->where('cat_pid', '=', $aoo['cat_pid'])
                            ->select('cat_id', 'cat_name', 'cat_pid', 'cat_level')
                            ->get()
                            ->toArray();
                        $second = [];
                        if (!empty($secondLevel)) {
                            foreach ($secondLevel as $boo) {
                                $boo['children'] = [];
                                $second[] = $boo;
                            }
                        }
                        $aoo['children'] = $second;
                        $result[] = $aoo;
                    }
                    break;
                case 3:
                    foreach ($firstLevel as $aoo) {
                        $aoo = $aoo->toArray();
                        $secondLevel = Category::query()
                            ->where('cat_pid', '=', $aoo['cat_pid'])
                            ->select('cat_id', 'cat_name', 'cat_pid', 'cat_level')
                            ->get()
                            ->toArray();
                        $second = [];
                        if (!empty($secondLevel)) {
                            foreach ($secondLevel as $boo) {
                                $thirdLevel = Category::query()
                                    ->where('cat_pid', '=', $boo['cat_pid'])
                                    ->select('cat_id', 'cat_name', 'cat_pid', 'cat_level')
                                    ->get()
                                    ->toArray();
                                $boo['children'] = $thirdLevel;
                                $second[] = $boo;
                            }
                        }
                        $aoo['children'] = $second;
                        $result[] = $aoo;
                    }
                    break;
            }
        }
        $response['data'] = $result;
        return [
            'code' => BaseConst::HTTP_SUCCESS_CODE,
            'msg' => BaseConst::USER_SUCCESS_GET_DATA,
            'data' => $response
        ];
    }

    /**
     * @param $param
     * @return array
     * @author zlq
     * @create_time 2020/10/13 10:30
     * @descrption 新增分类
     */
    public function addCategory($param)
    {
        // 查询父级分类是否存在
        $parentCategory = Category::query()
            ->find($param['catPid'])
            ->toArray();
        if (empty($parentCategory)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_ADD_ERROR_NO_PARENT_CATEGORY, 'data' => []];
        }
        // 验证等级是否正确
        $level = (int)$param['cat_level'];
        if (((int)$parentCategory['cat_level'] + 1) != $level) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_ADD_ERROR_ERROR_CATEGORY_LEVEL, 'data' => []];
        }
        $category = new Category([
            'cat_pid' => $parentCategory['cat_id'],
            'cat_name' => $param['catName'],
            'cat_level' => $param['catLevel'],
        ]);
        $result = $category->save();
        if ($result) {
            $categoryData = $category->toArray();
            $data = [
                'cat_id' => $categoryData['cat_id'],
                'cat_pid' => $categoryData['cat_pid'],
                'cat_name' => $categoryData['cat_name'],
                'cat_level' => $categoryData['cat_level'],
            ];
            return ['code' => BaseConst::HTTP_SUCCESS_CREATE_CODE, 'msg' => BaseConst::CATEGORY_ADD_ERROR_RESULT, 'data' => $data];
        } else {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_ADD_SUCCESS_RESULT, 'data' => []];
        }
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 11:02
     * @descrption 查询分类详情
     * @param $param
     * @return array
     */
    public function getCategoryInfo($param)
    {
        // 查询分类详情
        $categoryData = Category::query()
            ->find($param['cat_id'])
            ->toArray();
        if (empty($categoryData)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_GET_INFO_ERROR_NO_CATEGORY_DATA, 'data' => []];
        }
        $data = [
            'cat_id' => $categoryData['cat_id'],
            'cat_pid' => $categoryData['cat_pid'],
            'cat_name' => $categoryData['cat_name'],
            'cat_level' => $categoryData['cat_level'],
        ];
        return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::CATEGORY_GET_INFO_SUCCESS_RESULT, 'data' => $data];
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 11:22
     * @descrption 编辑分类信息
     * @param $param
     * @return array
     */
    public function editCategory($param)
    {
        // 查询分类详情
        $categoryData = Category::query()
            ->find($param['cat_id'])
            ->toArray();
        if (empty($categoryData)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_EDIT_ERROR_NO_CATEGORY_DATA, 'data' => []];
        }
        $data = [
            'cat_id' => $categoryData['cat_id'],
            'cat_pid' => $categoryData['cat_pid'],
            'cat_name' => $param['cat_name'],
            'cat_level' => $categoryData['cat_level'],
        ];
        // 如果没有做改动就不去修改数据库中的数据
        if ($categoryData['cat_name'] == $param['cat_name']) {
            return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::CATEGORY_EDIT_SUCCESS_RESULT, 'data' => $data];
        }
        // 修改真实数据
        $result = Category::query()
            ->where('cat_id', '=', $param['cat_id'])
            ->update(['cat_name' => $param['cat_name']]);
        if ($result) {
            return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::CATEGORY_EDIT_SUCCESS_RESULT, 'data' => $data];
        } else {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_EDIT_ERROR_RESULT, 'data' => []];
        }
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 11:29
     * @descrption 删除分类信息
     * @param $param
     * @return array
     */
    public function deleteCategory($param)
    {
        // 查询分类详情
        $categoryData = Category::query()
            ->find($param['cat_id'])
            ->toArray();
        if (empty($categoryData)) {
            return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::CATEGORY_DELETE_SUCCESS_RESULT, 'data' => []];
        }
        // 删除真实数据
        $result = Category::query()
            ->where('cat_id', '=', $param['cat_id'])
            ->delete();
        if ($result) {
            return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::CATEGORY_DELETE_SUCCESS_RESULT, 'data' => []];
        } else {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_DELETE_ERROR_RESULT, 'data' => []];
        }
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 13:52
     * @descrption 获取分类对应属性
     * @param $param
     * @return array
     */
    public function getCategoryAttr($param)
    {
        // 查询分类详情
        $categoryData = Category::query()
            ->find($param['cat_id'])
            ->toArray();
        if (empty($categoryData)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_GET_ATTR_ERROR_NO_CATEGORY_DATA, 'data' => []];
        }
        // 查询分类对应属性
        $attrList = Attribute::query()
            ->where([['cat_id', '=', $param['cat_id']], ['attr_sel', 'eq', $param['sel']]])
            ->get(['attr_id', 'attr_name', 'cat_id', 'attr_sel', 'attr_write', 'attr_vals'])
            ->toArray();
        return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::CATEGORY_DELETE_SUCCESS_RESULT, 'data' => $attrList];
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 14:30
     * @descrption 添加分类属性
     * @param $param
     * @return array
     */
    public function addCategoryAttr($param)
    {
        // 查询分类详情
        $categoryData = Category::query()
            ->find($param['cat_id'])
            ->toArray();
        if (empty($categoryData)) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_ADD_ATTR_ERROR_NO_CATEGORY_DATA, 'data' => []];
        }
        $attribute = new Attribute([
            'attr_name' => $param['attr_name'],
            'cat_id' => $param['cat_id'],
            'attr_sel' => $param['attr_sel'],
            'attr_write' => $param['attr_sel'] == 'only' ? 'manual' : 'list',
            'attr_vals' => $param['attr_vals']
        ]);
        $result = $attribute->save();
        if ($result) {
            $attributeData = $attribute->toArray();
            $data = [
                'attr_id' => $attributeData['attr_id'],
                'attr_name' => $attributeData['attr_name'],
                'cat_id' => $attributeData['cat_id'],
                'attr_sel' => $attributeData['attr_sel'],
                'attr_write' => $attributeData['attr_write'],
                'attr_vals' => $attributeData['attr_vals']
            ];
            return ['code' => BaseConst::HTTP_SUCCESS_CREATE_CODE, 'msg' => BaseConst::CATEGORY_ADD_ATTR_SUCCESS_RESULT, 'data' => $data];
        } else {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_ADD_ATTR_ERROR_RESULT, 'data' => []];
        }
    }

    /**
     * @author zlq
     * @create_time 2020/10/13 14:43
     * @descrption 删除分类下某属性
     * @param $param
     * @return array
     */
    public function deleteCategoryAttr($param)
    {
        // 查询属性数据详情
        $attributeData = Attribute::query()
            ->where([['cat_id', '=', $param['cat_id']], ['attr_id', '=', $param['attr_id']]])
            ->count();
        if ($attributeData < 1) {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_ATTR_DELETE_ERROR_NO_ATTR, 'data' => []];
        }
        $result = Attribute::query()
            ->where([['cat_id', '=', $param['cat_id']], ['attr_id', '=', $param['attr_id']]])
            ->delete();
        if ($result) {
            return ['code' => BaseConst::HTTP_SUCCESS_CODE, 'msg' => BaseConst::CATEGORY_ATTR_DELETE_SUCCESS_RESULT, 'data' => []];
        } else {
            return ['code' => BaseConst::HTTP_ERROR_BAD_REQUEST_CODE, 'msg' => BaseConst::CATEGORY_ATTR_DELETE_ERROR_RESULT, 'data' => []];
        }
    }
}
