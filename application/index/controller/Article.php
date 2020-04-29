<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Article as ArticleModel;
use app\index\model\Comment;
use app\index\model\Praise;
use app\index\model\User;
use app\index\model\Follow;
use app\index\model\Collect;
use app\index\model\Share;
use think\Request;

class Article extends Base
{
    // 搜索相关操作的方法
    public function search(Request $request)
    {
        $type = '文章查询结果';
        $condition = $request->param('condition');
        $condition = '%' . $condition . '%';
        $articles = ArticleModel::where('title', 'like', $condition)->whereOr('content', 'like', $condition)->paginate(5);
        $recommends = ArticleModel::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        $this->view->assign('recommends', $recommends);
        $this->view->assign('type', $type);
        $this->view->assign('articles', $articles);
        return $this->view->fetch('public/search');
    }

    // 显示write写文章页面
    public function write()
    {
        $this->isLogin();
        return $this->view->fetch('write');
    }

    // 处理写文章页面提交的数据
    public function checkWrite(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '发表失败';
            $data = $request->post();
            $data['author'] = $this->username;
            $result = ArticleModel::create($data);
            if ($result == true) {
                $status = 1;
                $message = '发表成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 显示编辑文章页面
    public function editArticle(Request $request)
    {
        $id = $request->param('id');
        $author = ArticleModel::get(['id' => $id, 'status' => 1])->value('author');
        if ($author != $this->username) {
            $this->error('文章被拉黑或id不准确', '/');
        }
        $articles = ArticleModel::get(['id', $id]);
        $this->view->assign('articles', $articles);
        return $this->view->fetch('edit');
    }

    // 处理文章编辑页面提交的数据
    public function checkEdit(Request $request)
    {

        if ($request->isAjax()) {
            $status = 0;
            $message = '修改失败';
            $data = $request->param();
            $condition = ['id' => $data['id']];
            $result = ArticleModel::update($data, $condition);
            if ($result == true) {
                $status = 1;
                $message = '修改成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 拉黑文章
    public function defriendArticle(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '拉黑失败';
            $id = $request->param('id');
            $result = ArticleModel::update(['status' => 0], ['id' => $id]);
            if ($result == true) {
                $status = 1;
                $message = '拉黑成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 恢复文章到正常状态
    public function normalArticle(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '恢复失败';
            $id = $request->param('id');
            $result = ArticleModel::update(['status' => 1], ['id' => $id]);
            if ($result == true) {
                $status = 1;
                $message = '恢复成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    //删除文章
    public function delArticle(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $id = $request->post('id');
            $result = ArticleModel::destroy($id);
            if ($result == true) {
                $status = 1;
                $message = '删除成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    public function post($id)
    {
        $result = ArticleModel::get(['id' => $id, 'status' => 1]);
        if ($result) {
            $articles = ArticleModel::get(['id', $id]);
            $comments = Comment::where(['article_id' => $id])->paginate(10);
            $author = ArticleModel::where('id', $id)->value('author');
            // 查找非主键字段得用关联数组
            $users = User::get(['username' => $author]);
            $followed = Follow::get(['username' => $this->username, 'author' => $author]);
            $praised =  Praise::get(['article_id' => $id, 'username' => $this->username]);
            $collected =  Collect::get(['article_id' => $id, 'username' => $this->username]);
            $shared =  Share::get(['article_id' => $id, 'username' => $this->username]);
            $recents = ArticleModel::where('author', $this->username)->field(['id', 'title'])->limit(5)->order('update_time', 'desc')->select();
            $praise_count = Praise::where('author', $author)->count();
            $comment_count = Comment::where('author', $author)->count();
            $this->view->assign('articles', $articles);
            $this->view->assign('collected', $collected);
            $this->view->assign('comments', $comments);
            $this->view->assign('followed', $followed);
            $this->view->assign('praised', $praised);
            $this->view->assign('praise_count', $praise_count);
            $this->view->assign('comment_count', $comment_count);
            $this->view->assign('recents', $recents);
            $this->view->assign('shared', $shared);
            $this->view->assign('users', $users);
            return $this->view->fetch('article');
        } else {
            $this->error('文章不存在或已被删除', '/');
        }
    }
}
