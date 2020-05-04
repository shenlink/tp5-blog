<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 截取字符串，适用utf-8编码
function truncate($text, $length)
{
    if (mb_strlen($text, 'utf-8') > $length) {
        return mb_substr($text, 0, $length, 'utf-8') . '...';
    }
    return $text;
}

// 获取管理页面的数据
function getAdminData($class, $datatables, $searchName, $field)
{
    $instance = new $class();
    //接受请求
    $datatables = request()->post();
    //得到排序的方式
    $order = $datatables['order'][0]['dir'];
    //得到排序字段的下标
    $order_column = $datatables['order'][0]['column'];
    //根据排序字段的下标得到排序字段
    $order_field = $datatables['columns'][$order_column]['data'];
    //得到limit参数
    $limit_start = $datatables['start'];
    $limit_length = $datatables['length'];
    //得到搜索的关键词
    $search = $datatables['search']['value'];

    //如有搜索行为，则按照姓名进行模糊查询

    if ($search) {
        if ($class == 'app\index\model\User') {
            $data = $instance::withTrashed()
                ->order("$order_field $order")
                ->limit($limit_start, $limit_length)
                ->where($searchName, 'LIKE', "%$search%")
                ->select();
            $keyword_all_data = $instance::withTrashed()
                ->where($searchName, 'LIKE', "%$search%")
                ->select();
            $total = count($keyword_all_data); //获取满足关键词的总记录数
        } else {
            $data = $instance
                ->order("$order_field $order")
                ->limit($limit_start, $limit_length)
                ->where($searchName, 'LIKE', "%$search%")
                ->select();
            $keyword_all_data = $instance
                ->where($searchName, 'LIKE', "%$search%")
                ->select();
            $total = count($keyword_all_data); //获取满足关键词的总记录数
        }
    } else {
        if ($class == 'app\index\model\User') {
            //没有关键词，则查询全部
            $data = $instance::withTrashed()
                ->field($field)
                ->order("$order_field $order")
                ->limit($limit_start, $limit_length)
                ->select();
            $total = $instance::withTrashed()->count();
        } else {
            //没有关键词，则查询全部
            $data = $instance
                ->field($field)
                ->order("$order_field $order")
                ->limit($limit_start, $limit_length)
                ->select();
            $total = $instance->count();
        }
    }

    if ($data) {
        $data = collection($data)->toArray();
    }
    $draw = request()->post('draw');
    $AllData = [
        // ajax的请求次数，创建唯一标识
        'draw' => $draw,
        // 结果数
        'recordsTotal' => count($data),
        // 总数据量
        'recordsFiltered' => $total,
        // 总数据
        'data' => $data,
    ];

    return json($AllData);
}
