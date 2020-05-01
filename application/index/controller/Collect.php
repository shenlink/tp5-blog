<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Collect as CollectModel;
use app\index\model\Article;
use think\Request;
use think\Db;

class Collect extends Base
{
    public function checkCollect(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
            $article_id = $data['article_id'];
            $data['username'] = $this->username;
            $result = CollectModel::get(['username' => $this->username, 'article_id' => $article_id]);
            if ($result) {
                $status = -11;
                $message = '取消失败';
                Db::startTrans();
                try {
                    $collectResult = CollectModel::where(['username' => $this->username, 'article_id' => $article_id])->delete();
                    $articleResult = Article::where('id', $article_id)->setDec('collect_count');

                    if (!($collectResult && $articleResult)) {
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
                $message = '收藏失败';
                Db::startTrans();
                try {
                    $collectResult = CollectModel::create($data);
                    $articleResult = Article::where('id', $article_id)->setInc('collect_count');

                    if (!($collectResult && $articleResult)) {
                        throw new \Exception('发生错误');
                    }
                    Db::commit();
                    $status = 1;
                    $message = '收藏成功';
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

    // 删除收藏
    public function delCollect(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $data = $request->post();
            $article_id = $data['article_id'];
            $id = $data['id'];
            Db::startTrans();
            try {
                $collectResult = CollectModel::where(['id'=>$id,'article_id'=>$article_id])->delete();
                $articleResult = Article::where('id', $article_id)->setDec('collect_count');

                if (!($collectResult && $articleResult)) {
                    throw new \Exception('发生错误');
                }
                Db::commit();
                $status = 1;
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
