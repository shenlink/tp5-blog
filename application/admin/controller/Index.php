<?php

namespace app\admin\controller;

use app\index\controller\Base;
use app\index\model\Article;
use app\index\model\Category;
use app\index\model\Comment;
use app\index\model\User;

class Index extends Base
{
    public function index()
    {
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        $userCount = User::withTrashed()->count();
        $articleCount = Article::count();
        $commentCount = Comment::count();
        $categoryCount = Category::count();
        $allCategory = Article::group('category')->column('category');
        $newUserCount = User::whereTime('create_time', 'today')->count();
        $newArticleCount = Article::whereTime('create_time', 'today')->count();
        $this->view->assign('userCount', $userCount);
        $this->view->assign('articleCount', $articleCount);
        $this->view->assign('commentCount', $commentCount);
        $this->view->assign('newUserCount', $newUserCount);
        $this->view->assign('categoryCount', $categoryCount);
        $this->view->assign('allCategory', $allCategory);
        $this->view->assign('newArticleCount', $newArticleCount);
        return $this->view->fetch('index');
    }

    public function user()
    {
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('user');
    }


    public function getUserData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            $class = 'app\index\model\User';
            $searchName = 'username';
            $field = 'id,username,role,article_count,follow_count,fans_count,create_time,status';
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    public function article()
    {
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('article');
    }

    public function getArticleData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            $class = 'app\index\model\Article';
            $searchName = 'title';
            $field = 'id,author,title,status,update_time,category,comment_count,praise_count,collect_count,share_count';
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    public function comment()
    {
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('comment');
    }

    public function getCommentData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            $class = 'app\index\model\Comment';
            $searchName = 'content';
            $field = 'id,content,comment_time,article_id,title,username';
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    public function category()
    {
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('category');
    }

    public function getCategoryData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            $class = 'app\index\model\Category';
            $searchName = 'category';
            $field = 'id,category,article_count,create_time';
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    public function announcement()
    {
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('announcement');
    }

    public function getAnnouncementData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            $class = 'app\index\model\Announcement';
            $searchName = 'content';
            $field = 'id,content,create_time';
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    public function message()
    {
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('message');
    }

    public function getMessageData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            $class = 'app\index\model\Message';
            $searchName = 'username';
            $field = 'id,username,content,message_time';
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }
}
