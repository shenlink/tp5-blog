<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Collect as CollectModel;
use think\Request;

class Collect extends Base
{
    public function checkCollect(Request $request)
    {
        $data = $request->post();
        $article_id = $data['article_id'];
        $data['username'] = $this->username;
        $result = CollectModel::get(['username' => $this->username, 'article_id' => $article_id]);
        if ($result) {
            $status = 00;
            $message = '取消失败';
            $cancel = CollectModel::destroy(['username' => $this->username, 'article_id' => $article_id]);
            if ($cancel == true) {
                $status = 11;
                $message = '取消成功';
                return ['status' => $status, 'message' => $message];
            } else {
                return ['status' => $status, 'message' => $message];
            }
        } else {
            $status = 0;
            $message = '收藏失败';
            $add = CollectModel::create($data);
            if ($add == true) {
                $status = 1;
                $message = '收藏成功';
                return ['status' => $status, 'message' => $message];
            } else {
                return ['status' => $status, 'message' => $message];
            }
        }
    }
}
