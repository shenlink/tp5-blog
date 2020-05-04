<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Announcement;
use app\index\model\Article;

class Index extends Base
{
    // 展示首页页面
    public function index()
    {
        // 获取所有的公告内容
        $announcements = Announcement::all();
        // 获取所有的文章并分页
        $articles = Article::order('update_time desc')->paginate(5);
        // 获取10篇推荐文章
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        // 模板赋值
        $this->view->assign('announcements', $announcements);
        $this->view->assign('articles', $articles);
        $this->view->assign('recommends', $recommends);
        return $this->view->fetch('index');
    }
}
