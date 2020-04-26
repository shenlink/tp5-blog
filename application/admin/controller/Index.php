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
        if ($this->username == 'shen') {
            $announcements = Announcement::all();
            $articles = Article::all();
            $AllCategorys = Category::all();
            $comments = Comment::all();
            $messages = Message::all();
            $users = User::all();
            $type = 'user';
            $this->view->assign('announcements', $announcements);
            $this->view->assign('articles', $articles);
            $this->view->assign('AllCategorys', $AllCategorys);
            $this->view->assign('comments', $comments);
            $this->view->assign('messages', $messages);
            $this->view->assign('type', $type);
            $this->view->assign('users', $users);
            return $this->view->fetch('admin');
        } else if ($this->username != $this->admin) {
            $this->view->assign('noadmin', 'noadmin');
            $this->view->display('error.html');
        } else {
            $this->view->assign('nologin', 'nologin');
            $this->view->display('error.html');
        }
    }
}
