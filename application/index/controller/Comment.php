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
        $result = CommentModel::insertGetId($data);
        if ($result != 0) {
            $status = 1;
            $message = '发表成功';
        }
        return ['status' => $status, 'message' => $message,'comment_id'=>$result];
    }

    // 删除评论
    public function delComment(Request $request)
    {
        $status = 0;
        $message = '删除失败';
        $data = $request->post();
        $result = CommentModel::destroy($data);
        if ($result == true) {
            $status = 1;
            $message = '删除成功';
        }
        return ['status' => $status, 'message' => $message];
    }
}
