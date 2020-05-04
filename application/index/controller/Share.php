<?php

namespace app\index\controller;

use think\Db;
use think\Request;
use app\index\controller\Base;
use app\index\model\Article;
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
            // 确认有没有分享
            $result = ShareModel::get(['username' => $this->username, 'article_id' => $article_id]);
            // 已经分享了
            if ($result) {
                $status = -11;
                $message = '取消失败';
                // 开启事务
                Db::startTrans();
                try {
                    // 删除分享记录
                    $shareResult = ShareModel::where(['username' => $this->username, 'article_id' => $article_id])->delete();
                    // Article表的share_count自减1
                    $articleResult = Article::where('id', $article_id)->setDec('share_count');
                    // 如果发生错误，就抛出异常
                    if (!($shareResult && $articleResult)) {
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
                $message = '分享失败';
                // 分享操作
                // 开启事务
                Db::startTrans();
                try {
                    // 添加分享记录
                    $shareResult = ShareModel::insert($data);
                    // Article表的share_count自增1
                    $articleResult = Article::where('id', $article_id)->setInc('share_count');
                    // 如果发生错误，就抛出异常
                    if (!($shareResult && $articleResult)) {
                        throw new \Exception('发生错误');
                    }
                    // 提交事务
                    Db::commit();
                    $status = 1;
                    $message = '分享成功';
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

    // 删除分享记录
    public function delShare(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $data = $request->post();
            $article_id = $data['article_id'];
            // 开启事务
            Db::startTrans();
            try {
                // 删除分享记录
                $shareResult = ShareModel::where(['username' => $this->username, 'article_id' => $article_id])->delete();
                // Article表的share_count自增1
                $articleResult = Article::where('id', $article_id)->setDec('share_count');
                // 如果发生错误，就抛出异常
                if (!($shareResult && $articleResult)) {
                    throw new \Exception('发生错误');
                }
                // 提交事务
                Db::commit();
                $status = 1;
                $message = '删除成功';
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
}
