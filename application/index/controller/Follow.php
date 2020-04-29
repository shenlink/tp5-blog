<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Follow as FollowModel;
use think\Request;

class Follow extends Base
{
    // 确认关注
    public function checkFollow(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
            $author = $data['author'];
            $data['username'] = $this->username;
            $result = FollowModel::get(['username' => $this->username, 'author' => $author]);
            if ($result) {
                $status = -11;
                $message = '取消失败';
                $cancel = FollowModel::destroy(['username' => $this->username, 'author' => $author]);
                if ($cancel == true) {
                    $status = 11;
                    $message = '取消关注';
                    return ['status' => $status, 'message' => $message];
                } else {
                    return ['status' => $status, 'message' => $message];
                }
            } else {
                $status = 0;
                $message = '关注失败';
                $add = FollowModel::create($data);
                if ($add == true) {
                    $status = 1;
                    $message = '关注成功';
                    return ['status' => $status, 'message' => $message];
                } else {
                    return ['status' => $status, 'message' => $message];
                }
            }
        } else {
            return $this->error('非法访问');
        }
    }

    // 取消关注
    public function delFollow(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '取消失败';
            $author = $request->post('author');
            $result = FollowModel::destroy(['username' => $this->username, 'author' => $author]);
            if ($result == true) {
                $status = 1;
                $message = '取消关注';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }
}
