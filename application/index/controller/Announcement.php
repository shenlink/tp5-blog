<?php

namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use app\index\model\Article;

class Announcement extends Base
{
    // 添加公告
    public function add()
    {
        $addAnnouncement = 'addAnnouncement';
        $recommends = Article::where('status', 1)->field(['article_id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        $this->view->assign('addAnnouncement', $addAnnouncement);
        $this->view->assign('recommends', $recommends);
        return $this->view->fetch('public/add');
    }


}
