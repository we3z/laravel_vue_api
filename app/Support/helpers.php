<?php
declare(strict_types=1);

if (!function_exists('format_data_tree')) {
    function format_data_tree($list, $pk='id', $pid='pid', $child='children', $root_name='level', $root=0)
    {
        $tree = array();
        $packData = array();
        foreach ($list as $data) {
            $packData[$data[$pk]] = $data;
        }
        foreach ($packData as $key => $val) {
            if ($val[$root_name] == $root) {
                //代表跟节点
                $tree[] =& $packData[$key];
            } else {
                //找到其父类
                $packData[$val[$pid]][$child][] =& $packData[$key];
            }
        }
        return $tree;
    }
}




