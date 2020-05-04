<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Article;
use app\index\model\Comment;
use app\index\model\Follow;
use app\index\model\Receive;

class Manage extends Base
{
    // 显示用户管理页面首页
    public function index()
    {
        $this->isLogin();
        $articleCount = Article::where('author', $this->username)->count();
        $commentCount = Comment::where('author', $this->username)->count();
        $fansCount = Follow::where('author', $this->username)->count();
        $followCount = Follow::where('author', $this->username)->count();
        $allCategory = Article::group('category')->column('category');
        $newArticleCount = Article::where('author', $this->username)->whereTime('create_time', 'today')->count();
        $newFansCount = Article::where('author', $this->username)->whereTime('create_time', 'today')->count();
        $this->view->assign('articleCount', $articleCount);
        $this->view->assign('commentCount', $commentCount);
        $this->view->assign('fansCount', $fansCount);
        $this->view->assign('followCount', $followCount);
        $this->view->assign('allCategory', $allCategory);
        $this->view->assign('newArticleCount', $newArticleCount);
        $this->view->assign('newFansCount', $newFansCount);
        return $this->view->fetch('index');
    }

    public function article()
    {
        $this->isLogin();
        return $this->view->fetch('article');
    }

    public function getArticleData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            $class = 'app\index\model\Article';
            $searchName = 'title';
            $where = 'author';
            $field = 'id,title,status,update_time,category,comment_count,praise_count,collect_count,share_count';
            $data = getManageData($class, $datatables, $searchName, $where, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    public function comment()
    {
        $this->isLogin();
        return $this->view->fetch('comment');
    }

    public function getCommentData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            $class = 'app\index\model\Comment';
            $searchName = 'content';
            $where = 'author';
            $field = 'id,username,content,comment_time,title,article_id';
            $data = getManageData($class, $datatables, $searchName, $where, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    public function follow()
    {
        $this->isLogin();
        return $this->view->fetch('follow');
    }


    public function getFollowData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            $class = 'app\index\model\Follow';
            $searchName = 'author';
            $where = 'username';
            $field = 'id,author,follow_time';
            $data = getManageData($class, $datatables, $searchName, $where, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    public function fans()
    {
        $this->isLogin();
        return $this->view->fetch('fans');
    }

    public function getFansData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            $class = 'app\index\model\Follow';
            $searchName = 'username';
            $where = 'author';
            $field = 'id,username,follow_time';
            $data = getManageData($class, $datatables, $searchName, $where, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    public function receive()
    {
        $this->isLogin();
        if ($this->username !== $this->admin) {
            return $this->view->fetch('receive');
        } else {
            return $this->error('非法请求');
        }
    }

    public function getReceiveData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            $class = 'app\index\model\Receive';
            $searchName = 'content';
            $where = 'username';
            $field = 'id,content,receive_time';
            $data = getManageData($class, $datatables, $searchName, $where, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }
}
