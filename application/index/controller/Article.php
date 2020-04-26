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

class Article extends Base
{
    // 显示write写文章页面
    public function write()
    {
        $this->isLogin();
        return $this->view->fetch('write');
    }

    public function _empty($article_id)
    {
        if (!is_numeric($article_id)) {
            $this->error('文章id不正确', '/');
        }
        $result = ArticleModel::get(['article_id' => $article_id, 'status' => 1]);
        if ($result) {
            $articles = ArticleModel::get(['article_id', $article_id]);
            $comments = Comment::get(['article_id', $article_id]);
            $author = ArticleModel::where('article_id', $article_id)->value('author');
            // 查找非主键字段得用关联数组
            $users = User::get(['username' => $author]);
            $followed = Follow::get(['username' => $this->username, 'author' => $author]);
            $praised =  Praise::get(['article_id' => $article_id, 'username' => $this->username]);
            $collected =  Collect::get(['article_id' => $article_id, 'username' => $this->username]);
            $shared =  Share::get(['article_id' => $article_id, 'username' => $this->username]);
            $recents = ArticleModel::where('author', $this->username)->field(['article_id', 'title'])->limit(5)->order('update_time', 'desc')->select();
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
