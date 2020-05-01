<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Follow as FollowModel;
use app\index\model\User;
use think\Request;
use think\Db;


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
                Db::startTrans();
                try {
                    $followResult = FollowModel::where(['author' => $author, 'username' => $this->username])->delete();
                    $fansCount = User::where('username', $author)->setDec('fans_count');
                    $followCount = User::where('username', $this->username)->setDec('follow_count');
                    if (!($followResult && $fansCount && $followCount)) {
                        throw new \Exception('发生错误');
                    }
                    Db::commit();
                    $status = 11;
                    $message = '取消成功';
                    return ['status' => $status, 'message' => $message];
                } catch (\Exception $e) {
                    Db::rollback();
                    return ['status' => $status, 'message' => $message];
                }
            } else {
                $status = 0;
                $message = '关注失败';
                Db::startTrans();
                try {
                    $followResult = FollowModel::create($data);
                    $fansCount = User::where('username', $author)->setInc('fans_count');
                    $followCount = User::where('username', $this->username)->setInc('follow_count');
                    if (!($followResult && $fansCount && $followCount)) {
                        throw new \Exception('发生错误');
                    }
                    Db::commit();
                    $status = 1;
                    $message = '关注成功';
                    return ['status' => $status, 'message' => $message];
                } catch (\Exception $e) {
                    Db::rollback();
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
            Db::startTrans();
            try {
                $followResult = FollowModel::where(['author' => $author, 'username' => $this->username])->delete();
                $fansCount = User::where('username', $author)->setDec('fans_count');
                $followCount = User::where('username', $this->username)->setDec('follow_count');
                if (!($followResult && $fansCount && $followCount)) {
                    throw new \Exception('发生错误');
                }
                Db::commit();
                $status = 1;
                $message = '取消成功';
                return ['status' => $status, 'message' => $message];
            } catch (\Exception $e) {
                Db::rollback();
                return ['status' => $status, 'message' => $message];
            }
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
            $type = '';
            if ($format == '"%k"') {
                for ($i = 0; $i < 24; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(0, 23);
                $type = 'hour';
            } else if ($format == '"%e"') {
                $days = date("t") + 1;
                for ($i = 1; $i < $days; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, $days - 1);
                $type = 'day';
            } else {
                for ($i = 1; $i < 13; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, 12);
                $type = 'month';
            }

            foreach ($newPerTime as $key => $value) {
                foreach ($result as $k => $v) {
                    if ($key == $k) {
                        $newPerTime[$key] = $v;
                    }
                }
            }
            $data = ['type' => $type, 'rangeTime' => $rangeTime, 'newPerTime' => $newPerTime];
            return json_encode($data);
        } else {
            return $this->error('非法访问');
        }
    }
}
