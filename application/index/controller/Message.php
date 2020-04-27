<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Article;
use think\Request;
use app\index\model\Message as MessageModel;

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

    // 处理私信数据
    public function checkAddMessage(Request $request)
    {
        $status = 0;
        $message = '发送失败';
        $data = $request->param();
        $result = MessageModel::create($data);
        if ($result == true) {
            $status = 1;
            $message = '发送成功';
        }
        return ['status' => $status, 'message' => $message];
    }
}
