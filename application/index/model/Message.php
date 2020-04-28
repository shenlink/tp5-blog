<?php
namespace app\index\model;

use think\Model;

class Message extends Model
{
    // 是否需要自动写入时间戳
    protected $autoWriteTimestamp = true;
    // 创建时间字段
    protected $createTime = 'message_time';
    // 更新时间字段
    protected $updateTime = '';
}