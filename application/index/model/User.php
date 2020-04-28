<?php

namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class User extends Model
{
    //导入软删除方法集
    use SoftDelete;
    //设置软删除字段，只有该字段为NULL,该字段才会显示出来
    protected $deleteTime = 'delete_time';
    // 保存自动完成列表
    protected $auto = [
        'delete_time' => NULL,
        'is_delete' => 1,
    ];
    // 更新自动完成列表
    protected $update = [];
    // 是否需要自动写入时间戳，如果设置为字符串，则表示时间字段的类型
    protected $autoWriteTimestamp = true;
    // 创建时间字段
    protected $createTime = 'create_time';
    // 更新时间字段
    protected $updateTime = '';
    // 时间字段取出后的默认时间格式
    protected $dateFormat = 'Y-m-d H:m:s';
    //数据表中角色字段:role返回值处理
    public function getRoleAttr($value)
    {
        $role = [
            0 => '普通用户',
            1 => '管理员'
        ];
        return $role[$value];
    }

    //状态字段:status返回值处理
    public function getStatusAttr($value)
    {
        $status = [
            0 => '拉黑',
            1 => '正常'
        ];
        return $status[$value];
    }

    //密码修改器
    public function setPasswordAttr($value)
    {
        return md5($value);
    }
}
