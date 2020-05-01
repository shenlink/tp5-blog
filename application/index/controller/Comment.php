<?php

namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use app\index\model\Comment as CommentModel;
use app\index\model\Article;
use think\Db;


class Comment extends Base
{
    // 发表评论
    public function addComment(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '发表失败';
            $data = $request->post();
            $data['username'] = $this->username;
            $article_id = $data['article_id'];

            Db::startTrans();
            try {
                $commentResult = CommentModel::insertGetId($data);
                $articleResult = Article::where('id', $article_id)->setInc('comment_count');

                if (!($commentResult && $articleResult)) {
                    throw new \Exception('发生错误');
                }
                Db::commit();
                $status = 1;
                $message = '发表成功';
                return ['status' => $status, 'message' => $message, 'id' => $commentResult];
            } catch (\Exception $e) {
                Db::rollback();
                return ['status' => $status, 'message' => $message];
            }
        } else {
            return $this->error('非法访问');
        }
    }

    // 删除评论
    public function delComment(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $data = $request->post();
            $article_id = $data['article_id'];
            $id = $data['id'];
            Db::startTrans();
            try {
                $commentResult = CommentModel::where(['id' => $id, 'article_id' => $article_id])->delete();
                $articleResult = Article::where('id', $article_id)->setDec('comment_count');

                if (!($commentResult && $articleResult)) {
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
