<?php

namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use app\index\model\Share as ShareModel;
use think\Db;

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
                Db::startTrans();
                try {
                    $shareResult = Db::table('share')->where(['username' => $this->username, 'article_id' => $article_id])->delete();
                    $articleResult = Db::table('article')->where('id', $article_id)->setDec('share_count');

                    if (!($shareResult && $articleResult)) {
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
                $message = '分享失败';
                Db::startTrans();
                try {
                    $shareResult = Db::table('share')->insert($data);
                    $articleResult = Db::table('article')->where('id', $article_id)->setInc('share_count');

                    if (!($shareResult && $articleResult)) {
                        throw new \Exception('发生错误');
                    }
                    Db::commit();
                    $status = 1;
                    $message = '分享成功';
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

    // 删除分享记录
    public function delShare(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $data = $request->post();
            $article_id = $data['article_id'];
            Db::startTrans();
            try {
                $shareResult = Db::table('share')->where(['username' => $this->username, 'article_id' => $article_id])->delete();
                $articleResult = Db::table('article')->where('id', $article_id)->setDec('share_count');

                if (!($shareResult && $articleResult)) {
                    throw new \Exception('发生错误');
                }
                Db::commit();
                $status = 1;
                $message = '删除成功';
                return ['status' => $status, 'message' => $message];
            } catch (\Exception $e) {
                Db::rollback();
                return ['status' => $status, 'message' => $message];
            }
        } else {
            return $this->error('非法访问');
        }
    }
}
