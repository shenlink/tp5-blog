<?php
namespace app\index\model;

use think\Model;

class Article extends Model
{
    // 是否需要自动写入时间戳
    protected $autoWriteTimestamp = true;
    // 创建时间字段
    protected $createTime = 'create_time';
    // 更新时间字段
    protected $updateTime = 'update_time';
    //状态字段:status返回值处理
    public function getStatusAttr($value)
    {
        $status = [
            0 => '拉黑',
            1 => '正常'
        ];
        return $status[$value];
    }
}