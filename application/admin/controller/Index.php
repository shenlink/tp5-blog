<?php

namespace app\admin\controller;

use app\index\controller\Base;
use app\index\model\Announcement;
use app\index\model\Article;
use app\index\model\Category;
use app\index\model\Comment;
use app\index\model\Message;
use app\index\model\User;

class Index extends Base
{
    public function index()
    {
        $this->isLogin();
        if ($this->username == $this->admin) {
            return $this->view->fetch('index');
        } else {
            $this->error('你不是管理员', '/');
        }
    }

    public function user()
    {
        $users = User::order('create_time desc')->paginate(5);
        $this->view->assign('users', $users);
        return $this->view->fetch('user');
    }

    public function article()
    {
        $articles = Article::order('create_time desc')->paginate(5);
        $this->view->assign('articles', $articles);
        return $this->view->fetch('article');
    }

    public function comment()
    {
        $comments = Comment::order('comment_time desc')->paginate(5);
        $this->view->assign('comments', $comments);
        return $this->view->fetch('comment');
    }
    
    public function category()
    {
        $AllCategorys = Category::order('create_time desc')->paginate(5);
        $this->view->assign('AllCategorys', $AllCategorys);
        return $this->view->fetch('category');
    }

    public function announcement()
    {
        $announcements = Announcement::order('create_time desc')->paginate(5);
        $this->view->assign('announcements', $announcements);
        return $this->view->fetch('announcement');
    }

    public function message()
    {
        $messages = Message::order('message_time desc')->paginate(5);
        $this->view->assign('messages', $messages);
        return $this->view->fetch('message');
    }
}
