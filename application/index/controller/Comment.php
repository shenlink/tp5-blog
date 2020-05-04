<?php

namespace app\index\controller;

use think\Db;
use think\Request;
use app\index\controller\Base;
use app\index\model\Article;
use app\index\model\Comment as CommentModel;

class Comment extends Base
{
    // 确认发表评论
    public function addComment(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '发表失败';
            $data = $request->post();
            $data['username'] = $this->username;
            $article_id = $data['article_id'];
            // 开启事务
            Db::startTrans();
            try {
                // 添加评论记录，并返回新增的记录的id
                $commentResult = CommentModel::insertGetId($data);
                // Article表的comment_count自增1
                $articleResult = Article::where('id', $article_id)->setInc('comment_count');
                // 如果发生错误，就抛出异常
                if (!($commentResult && $articleResult)) {
                    throw new \Exception('发生错误');
                }
                // 提交事务
                Db::commit();
                $status = 1;
                $message = '发表成功';
                return ['status' => $status, 'message' => $message, 'id' => $commentResult];
            } catch (\Exception $e) {
                // 回滚
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
            // 开启事务
            Db::startTrans();
            try {
                // 删除了记录
                $commentResult = CommentModel::where(['id' => $id, 'article_id' => $article_id])->delete();
                // Article表的comment_count自增1
                $articleResult = Article::where('id', $article_id)->setDec('comment_count');
                // 如果发生错误，就抛出异常
                if (!($commentResult && $articleResult)) {
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
