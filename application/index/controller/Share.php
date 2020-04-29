<?php

namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use app\index\model\Share as ShareModel;

class Share extends Base
{
    // 确认分享
    public function checkShare(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
            $article_id = $data['article_id'];
            $data['username'] = $this->username;
            $result = ShareModel::get(['username' => $this->username, 'article_id' => $article_id]);
            if ($result) {
                $status = -11;
                $message = '取消失败';
                $cancel = ShareModel::destroy(['username' => $this->username, 'article_id' => $article_id]);
                if ($cancel == true) {
                    $status = 11;
                    $message = '取消成功';
                    return ['status' => $status, 'message' => $message];
                } else {
                    return ['status' => $status, 'message' => $message];
                }
            } else {
                $status = 0;
                $message = '分享失败';
                $add = ShareModel::create($data);
                if ($add == true) {
                    $status = 1;
                    $message = '分享成功';
                    return ['status' => $status, 'message' => $message];
                } else {
                    return ['status' => $status, 'message' => $message];
                }
            }
        } else {
            return $this->error('非法访问');
        }
    }

    // 删除分享记录
    public function delShare(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $data = $request->post();
            $result = ShareModel::destroy($data);
            if ($result == true) {
                $status = 1;
                $message = '删除成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }
}
