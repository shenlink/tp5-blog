<?php

namespace app\model;

use core\lib\Model;

class Announcement extends Model
{

    private static $announcement;
    public static function getInstance()
    {
        if (self::$announcement) {
            return self::$announcement;
        } else {
            self::$announcement = new self();
            return self::$announcement;
        }
    }

    // 获取公告信息
    public function getAnnouncement()
    {
        return $this->table('announcement')->field('content')->selectAll();
    }

    // 获取公告信息
    public function getOneAnnouncement($announcement_id)
    {
        return $this->table('announcement')->where(['announcement_id'=>"{$announcement_id}"])->select();
    }

    // 查询announcement表中的数据
    public function getAllAnnouncement($currentPage=1, $pageSize)
    {
        return $this->table('announcement')->field('announcement_id,content,created_at')-> pages($currentPage, $pageSize, '/admin/manage', 'announcement');
    }

    // 添加公告
    public function checkAddAnnouncement($content, $created_at)
    {
        return $this->table('announcement')->insert(['content'=>"{$content}", 'created_at'=>"{$created_at}"]);
    }

    // 修改公告
    public function checkChangeAnnouncement($content, $announcement_id)
    {
        return $this->table('announcement')->where(['announcement_id'=>"{$announcement_id}"])->update(['content'=>"{$content}", 'announcement_id'=>"{$announcement_id}"]);
    }

    // 删除公告
    public function delAnnouncement($announcement_id)
    {
        return $this->table('announcement')->where(['announcement_id'=> "{$announcement_id}"])->delete();
    }

}