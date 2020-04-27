<?php

namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use app\index\model\Receive as ReceiveModel;

class Receive extends Base
{
    // 删除收到的私信
    public function delReceive(Request $request)
    {
        $status = 0;
        $message = '删除失败';
        $receive_id = $request->post('receive_id');
        $result = ReceiveModel::destroy($receive_id);
        if ($result == true) {
            $status = 1;
            $message = '删除成功';
        }
        return ['status' => $status, 'message' => $message];
    }
}
