<?php

namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use app\index\model\Praise as PraiseModel;

class Praise extends Base
{
    // 确认点赞
    public function checkPraise(Request $request)
    {
        $data = $request->post();
        $article_id = $data['article_id'];
        $data['username'] = $this->username;
        $result = PraiseModel::get(['username' => $this->username, 'article_id' => $article_id]);
        if ($result) {
            $status = -11;
            $message = '取消失败';
            $cancel = PraiseModel::destroy(['username' => $this->username, 'article_id' => $article_id]);
            if ($cancel == true) {
                $status = 11;
                $message = '取消成功';
                return ['status' => $status, 'message' => $message];
            } else {
                return ['status' => $status, 'message' => $message];
            }
        } else {
            $status = 0;
            $message = '点赞失败';
            $add = PraiseModel::create($data);
            if ($add == true) {
                $status = 1;
                $message = '点赞成功';
                return ['status' => $status, 'message' => $message];
            } else {
                return ['status' => $status, 'message' => $message];
            }
        }
    }
}
