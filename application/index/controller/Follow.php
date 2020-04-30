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

    // 每天新增的用户
    public function getNewFansCount(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
            $time = $data['time'];
            $format = $data['format'];
            // 当天新增多少个
            $result = FollowModel::whereTime('follow_time', $time)->column("id,FROM_UNIXTIME(follow_time, $format)");
            $result = array_count_values($result);
            $newPerTime = [];
            if ($format == '"%H"') {
                for ($i = 1; $i < 25; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, 24);
            } else if ($format == '"%D"') {
                $days = date("t") + 1;
                for ($i = 1; $i < $days; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, $days - 1);
            } else {
                for ($i = 1; $i < 13; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, 12);
            }

            foreach ($newPerTime as $key => $value) {
                foreach ($result as $k => $v) {
                    if ($key == $k) {
                        $newPerTime[$key] = $v;
                    }
                }
            }
            $data = ['rangeTime' => $rangeTime, 'newPerTime' => $newPerTime];
            return json_encode($data);
        } else {
            return $this->error('非法访问');
        }
    }
}
