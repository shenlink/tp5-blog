<?php

namespace app\index\controller;

use think\Db;
use think\Request;
use app\index\controller\Base;
use app\index\model\Article;
use app\index\model\Collect as CollectModel;

class Collect extends Base
{
    // 确认收藏
    public function checkCollect(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
            $article_id = $data['article_id'];
            $data['username'] = $this->username;
            // 确认有没有收藏
            $result = CollectModel::get(['username' => $this->username, 'article_id' => $article_id]);
            // 已经收藏了
            if ($result) {
                $status = -11;
                $message = '取消失败';
                // 开启事务
                Db::startTrans();
                try {
                    // 取消收藏
                    $collectResult = CollectModel::where(['username' => $this->username, 'article_id' => $article_id])->delete();
                    // Article表的collect_count自减1
                    $articleResult = Article::where('id', $article_id)->setDec('collect_count');
                    // 如果发生错误，就抛出异常
                    if (!($collectResult && $articleResult)) {
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
                $message = '收藏失败';
                // 收藏操作
                // 开启事务
                Db::startTrans();
                try {
                    // 添加收藏记录
                    $collectResult = CollectModel::create($data);
                    // Article表的collect_count自增1
                    $articleResult = Article::where('id', $article_id)->setInc('collect_count');
                    // 如果发生错误，就抛出异常
                    if (!($collectResult && $articleResult)) {
                        throw new \Exception('发生错误');
                    }
                    // 提交事务
                    Db::commit();
                    $status = 1;
                    $message = '收藏成功';
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

    // 删除收藏
    public function delCollect(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $data = $request->post();
            $article_id = $data['article_id'];
            $id = $data['id'];
            // 斯凯奇事务
            Db::startTrans();
            try {
                // 删除收藏记录
                $collectResult = CollectModel::where(['id'=>$id,'article_id'=>$article_id])->delete();
                // Article表的collect_count自增1
                $articleResult = Article::where('id', $article_id)->setDec('collect_count');
                // 如果发生错误，就抛出异常
                if (!($collectResult && $articleResult)) {
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
}
