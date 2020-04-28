<?php

namespace app\admin\controller;

use app\index\controller\Base;
use app\index\model\Announcement;
use app\index\model\Article;
use app\index\model\Category;
use app\index\model\Comment;
use app\index\model\Message;
use app\index\model\User;
use think\Request;

class Index extends Base
{
    public function index(Request $request)
    {
        $this->isLogin();
        if ($this->username == $this->admin) {
            $type = $request->param('type') ?? 'user';
            $announcements = Announcement::order('create_time desc')->paginate(5);
            $articles = Article::order('create_time desc')->paginate(5);
            $AllCategorys = Category::order('create_time desc')->paginate(5);
            $comments = Comment::order('create_time desc')->paginate(5);
            $messages = Message::order('create_time desc')->paginate(5);
            $users = User::order('create_time desc')->paginate(5);
            $this->view->assign('announcements', $announcements);
            $this->view->assign('articles', $articles);
            $this->view->assign('AllCategorys', $AllCategorys);
            $this->view->assign('comments', $comments);
            $this->view->assign('messages', $messages);
            $this->view->assign('type', $type);
            $this->view->assign('users', $users);
            return $this->view->fetch('admin');
        } else {
            $this->error('你不是管理员', '/');
        }
    }
}
