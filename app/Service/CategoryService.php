<?php
namespace App\Service;

use app\ApiConst\BaseConst;
use App\Models\GoodsCategory;
use Illuminate\Support\Facades\DB;

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
            $firstLevel = GoodsCategory::query()
                ->where('parent_id', '=', 0)
                ->limit($param['pageSize'])
                ->paginate($param['pageSize'], ['cat_id', 'cat_name', 'parent_id as cat_pid'], 'page', $param['pageNum']);
            $response = ['totalPage' => $firstLevel->total(), 'pageNum' => $param['pageNum'], 'data' => []];
        } else {
            $firstLevel = GoodsCategory::query()
                ->where('parent_id', '=', 0)
                ->select('cat_id', 'cat_name', 'parent_id as cat_pid')
                ->get();
            $response = ['totalPage' => 1, 'pageNum' => 1, 'data' => []];
        }
        $result = [];
        if (!empty($firstLevel)) {
            switch ($param['type']) {
                case 1:
                    foreach ($firstLevel as $aoo) {
                        $aoo = $aoo->toArray();
                        $aoo['cat_level'] = 0;
                        $aoo['cat_deleted'] = false;
                        $result[] = $aoo;
                    }
                    break;
                case 2:
                    foreach ($firstLevel as $aoo) {
                        $aoo = $aoo->toArray();
                        $secondLevel = GoodsCategory::query()
                            ->where('parent_id', '=', $aoo['cat_pid'])
                            ->select('cat_id', 'cat_name', 'parent_id as cat_pid')
                            ->get()
                            ->toArray();
                        $second = [];
                        if (!empty($secondLevel)) {
                            foreach ($secondLevel as $boo) {
                                $boo['cat_level'] = 1;
                                $boo['cat_deleted'] = false;
                                $boo['children'] = [];
                                $second[] = $boo;
                            }
                        }
                        $aoo['cat_level'] = 0;
                        $aoo['cat_deleted'] = false;
                        $aoo['children'] = $second;
                        $result[] = $aoo;
                    }
                    break;
                case 3:
                    foreach ($firstLevel as $aoo) {
                        $aoo = $aoo->toArray();
                        $secondLevel = GoodsCategory::query()
                            ->where('parent_id', '=', $aoo['cat_pid'])
                            ->select('cat_id', 'cat_name', 'parent_id as cat_pid')
                            ->get()
                            ->toArray();
                        $second = [];
                        if (!empty($secondLevel)) {
                            foreach ($secondLevel as $boo) {
                                $thirdLevel = GoodsCategory::query()
                                    ->where('parent_id', '=', $boo['cat_pid'])
                                    ->select('cat_id', 'cat_name', 'parent_id as cat_pid', Db::raw('2 as cat_level'), Db::raw('false as cat_deleted'))
                                    ->get()
                                    ->toArray();
                                $boo['cat_level'] = 1;
                                $boo['cat_deleted'] = false;
                                $boo['children'] = $thirdLevel;
                                $second[] = $boo;
                            }
                        }
                        $aoo['cat_level'] = 0;
                        $aoo['cat_deleted'] = false;
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

    public function addCategory($param)
    {

    }
}
