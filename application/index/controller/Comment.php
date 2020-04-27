<?php

namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use app\index\model\Comment as CommentModel;

class Comment extends Base
{
    // 发表评论
    public function addComment(Request $request)
    {
        $status = 0;
        $message = '发表失败';
        $data = $request->post();
        $data['username'] = $this->username;
        $result = CommentModel::create($data);
        if ($result == true) {
            $status = 1;
            $message = '发表成功';
        }
        return ['status' => $status, 'message' => $message];
    }
}
