<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Announcement;
use app\index\model\Article;

class Index extends Base
{
    public function index()
    {
        $announcements = Announcement::all();
        $articles = Article::order('update_time desc')->paginate(5);
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        $this->view->assign('announcements', $announcements);
        $this->view->assign('articles', $articles);
        $this->view->assign('recommends', $recommends);
        return $this->view->fetch('index');
    }
}
