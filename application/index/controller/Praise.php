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
            $result = PraiseModel::get(['username' => $this->username, 'article_id' => $article_id]);
            if ($result) {
                $status = -11;
                $message = '取消失败';
                Db::startTrans();
                try {
                    $praiseResult = PraiseModel::where(['username' => $this->username, 'article_id' => $article_id])->delete();
                    $articleResult = Article::where('id', $article_id)->setDec('praise_count');

                    if (!($praiseResult && $articleResult)) {
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
                $message = '点赞失败';
                Db::startTrans();
                try {
                    $praiseResult = PraiseModel::create($data);
                    $articleResult = Article::where('id', $article_id)->setInc('praise_count');

                    if (!($praiseResult && $articleResult)) {
                        throw new \Exception('发生错误');
                    }
                    Db::commit();
                    $status = 1;
                    $message = '点赞成功';
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

    // 删除点赞记录
    public function delPraise(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $data = $request->post();
            $article_id = $data['article_id'];
            Db::startTrans();
            try {
                $praiseResult = PraiseModel::where(['username' => $this->username, 'article_id' => $article_id])->delete();
                $articleResult = Article::where('id', $article_id)->setDec('praise_count');

                if (!($praiseResult && $articleResult)) {
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
            return $this->error('非法访问');
        }
    }
}
