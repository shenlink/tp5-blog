<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Article;
use think\Request;

class Message extends Base
{
    // 发私信
    public function addMessage(Request $request)
    {
        $username = $request->param('username');
        $recommends = Article::where('status', 1)->field(['article_id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        $this->view->assign('author', $username);
        $this->view->assign('recommends', $recommends);
        return $this->view->fetch('public/add');
    }
}
