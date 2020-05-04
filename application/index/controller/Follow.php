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
            // 确认是否已经关注
            $result = FollowModel::get(['username' => $this->username, 'author' => $author]);
            // 已经关注
            if ($result) {
                $status = -11;
                $message = '取消失败';
                // 开启事务
                Db::startTrans();
                try {
                    // 取消关注
                    $followResult = FollowModel::where(['author' => $author, 'username' => $this->username])->delete();
                    // User表中的作者的fans_count自减1
                    $fansCount = User::where('username', $author)->setDec('fans_count');
                    // User表中的当前胡的follow_count自减1
                    $followCount = User::where('username', $this->username)->setDec('follow_count');
                    // 如果发生错误，就抛出异常
                    if (!($followResult && $fansCount && $followCount)) {
                        throw new \Exception('发生错误');
                    }
                    // 提交事务
                    Db::commit();
                    $status = 11;
                    $message = '取消成功';
                    return ['status' => $status, 'message' => $message];
                } catch (\Exception $e) {
                    // 回滚
                    Db::rollback();
                    return ['status' => $status, 'message' => $message];
                }
            } else {
                $status = 0;
                $message = '关注失败';
                // 关注操作
                // 开启事务
                Db::startTrans();
                try {
                    // 添加关注记录
                    $followResult = FollowModel::create($data);
                    // User表中的作者的fans_count自增1
                    $fansCount = User::where('username', $author)->setInc('fans_count');
                    // User表中的作者的follow_count自增1
                    $followCount = User::where('username', $this->username)->setInc('follow_count');
                    // 如果发生错误，就抛出异常
                    if (!($followResult && $fansCount && $followCount)) {
                        throw new \Exception('发生错误');
                    }
                    // 提交事务
                    Db::commit();
                    $status = 1;
                    $message = '关注成功';
                    return ['status' => $status, 'message' => $message];
                } catch (\Exception $e) {
                    // 回滚
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
            // 开启事务
            Db::startTrans();
            try {
                // 取消关注
                $followResult = FollowModel::where(['author' => $author, 'username' => $this->username])->delete();
                // User表中的作者的fans_count自减1
                $fansCount = User::where('username', $author)->setDec('fans_count');
                // User表中的作者的follow_count自减1
                $followCount = User::where('username', $this->username)->setDec('follow_count');
                // 如果发生错误，就抛出异常
                if (!($followResult && $fansCount && $followCount)) {
                    throw new \Exception('发生错误');
                }
                // 提交事务
                Db::commit();
                $status = 1;
                $message = '取消成功';
                return ['status' => $status, 'message' => $message];
            } catch (\Exception $e) {
                // 回滚
                Db::rollback();
                return ['status' => $status, 'message' => $message];
            }
        } else {
            return $this->error('非法访问');
        }
    }

    // 新增的粉丝
    public function getNewFansCount(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
            $time = $data['time'];
            $format = $data['format'];
            // 新增多少个
            $result = FollowModel::whereTime('follow_time', $time)->column("id,FROM_UNIXTIME(follow_time, $format)");
            $result = array_count_values($result);
            $newPerTime = [];
            $type = '';
            if ($format == '"%k"') {
                // 获取一个一维数组，表示一天
                for ($i = 0; $i < 24; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(0, 23);
                $type = 'hour';
            } else if ($format == '"%e"') {
                $days = date("t") + 1;
                // 获取一个一维数组，表示一月
                for ($i = 1; $i < $days; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, $days - 1);
                $type = 'day';
            } else {
                // 获取一个一维数组，表示一年
                for ($i = 1; $i < 13; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, 12);
                $type = 'month';
            }
            // 循环遍历$newPerTime
            foreach ($newPerTime as $key => $value) {
                foreach ($result as $k => $v) {
                    // 当$newPerTime的key与$result的key相同，比如14==14,14点就有2篇文章
                    if ($key == $k) {
                        $newPerTime[$key] = $v;
                    }
                }
            }
            // type是时间范围
            $data = ['type' => $type, 'rangeTime' => $rangeTime, 'newPerTime' => $newPerTime];
            return json_encode($data);
        } else {
            return $this->error('非法访问');
        }
    }
}
