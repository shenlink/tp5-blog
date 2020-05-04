<?php

namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use app\index\model\Praise as PraiseModel;
use app\index\model\Article;
use think\Db;

class Praise extends Base
{
    // 确认点赞
    public function checkPraise(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
            $article_id = $data['article_id'];
            $data['username'] = $this->username;
            // 确认有没有点赞
            $result = PraiseModel::get(['username' => $this->username, 'article_id' => $article_id]);
            // 已经点赞了
            if ($result) {
                $status = -11;
                $message = '取消失败';
                // 开启事务
                Db::startTrans();
                try {
                    // 删除点赞记录
                    $praiseResult = PraiseModel::where(['username' => $this->username, 'article_id' => $article_id])->delete();
                    // Article表的praise_count自减1
                    $articleResult = Article::where('id', $article_id)->setDec('praise_count');
                    // 如果发生错误，就抛出异常
                    if (!($praiseResult && $articleResult)) {
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
                $message = '点赞失败';
                // 点赞操作
                // 开启事务
                Db::startTrans();
                try {
                    // 添加点赞记录
                    $praiseResult = PraiseModel::create($data);
                    // Article表的praise_count自增1
                    $articleResult = Article::where('id', $article_id)->setInc('praise_count');
                    // 如果发生错误，就抛出异常
                    if (!($praiseResult && $articleResult)) {
                        throw new \Exception('发生错误');
                    }
                    // 提交事务
                    Db::commit();
                    $status = 1;
                    $message = '点赞成功';
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

    // 删除点赞记录
    public function delPraise(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $data = $request->post();
            $article_id = $data['article_id'];
            // 开启事务
            Db::startTrans();
            try {
                // 删除点赞记录
                $praiseResult = PraiseModel::where(['username' => $this->username, 'article_id' => $article_id])->delete();
                // Article表的praise_count自减1
                $articleResult = Article::where('id', $article_id)->setDec('praise_count');
                // 如果发生错误，就抛出异常
                if (!($praiseResult && $articleResult)) {
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
            return $this->error('非法访问');
        }
    }
}
