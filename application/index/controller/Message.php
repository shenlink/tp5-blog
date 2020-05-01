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
    // 发私信
    public function sendMessage(Request $request)
    {
        $username = $request->param('username');
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
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
            Db::startTrans();
            try {
                $messageResult = MessageModel::create($data);
                $receiveResult = Receive::create($data);

                if (!($messageResult && $receiveResult)) {
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
