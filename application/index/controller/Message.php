<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Article;
use think\Request;
use app\index\model\Message as MessageModel;
use app\index\model\Receive;
use think\Db;


class Message extends Base
{
    // 展示发私信页面
    public function sendMessage(Request $request)
    {
        // 获取要接收的用户的用户名
        $username = $request->param('username');
        // 获取10篇推荐文章
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        // 模板赋值
        $this->view->assign('author', $username);
        $this->view->assign('recommends', $recommends);
        return $this->view->fetch('public/add');
    }

    // 处理私信数据
    public function checkMessage(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '发送失败';
            $data = $request->post();
            // 开启事务
            Db::startTrans();
            try {
                // 添加私信记录
                $messageResult = MessageModel::create($data);
                // 添加私信记录
                $receiveResult = Receive::create($data);
                // 如果发生错误，就抛出异常
                if (!($messageResult && $receiveResult)) {
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
        } else {
            return $this->error('非法访问');
        }
    }

    // 删除私信
    public function delMessage(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $id = $request->post('id');
            // 删除指定id的私信
            $result = MessageModel::destroy($id);
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
