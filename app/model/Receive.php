<?php

namespace app\model;

use core\lib\Model;

class Receive extends Model
{

    private static $receive;
    public static function getInstance()
    {
        if (self::$receive) {
            return self::$receive;
        } else {
            self::$receive = new self();
            return self::$receive;
        }
    }

    // 删除私信
    public function delReceive($receive_id)
    {
        return $this->table('receive')->where(['receive_id' => "{$receive_id}"])->delete();
    }

    public function getReceive($username,$currentPage=1, $pageSize=5)
    {
        return $this->table('receive')->field('receive_id,content,receive_at')->where(['username'=>"{$username}"])->pages($currentPage, $pageSize, '/user/manage','receive');
    }
}
