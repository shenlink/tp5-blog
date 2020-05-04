<?php

namespace app\index\controller;

use think\Request;
use app\index\controller\Base;
use app\index\model\Receive as ReceiveModel;

class Receive extends Base
{
    // 删除收到的私信
    public function delReceive(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $id = $request->post('id');
            // 删除指定id的私信
            $result = ReceiveModel::destroy($id);
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
