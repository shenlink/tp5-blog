<?php

namespace app\controller;

use core\lib\Controller;

class Comment extends Controller
{

    // 显示404页面
    public function displayNone()
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
    }

    // 发表评论
    public function addComment()
    {
        if (isset($_POST['article_id'])  && isset($_POST['author']) && isset($_POST['title']) && isset($_POST['content'])) {
            $article_id = $_POST['article_id'];
            $author = $_POST['author'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $result = $this->comment->addComment($article_id, $author, $title,  $content, $this->username, $this->time);
            echo $result ? $result : '0';
        } else {
            $this->displayNone();
        }
    }

    // 删除评论
    public function delComment()
    {
        if (isset($_POST['article_id']) && isset($_POST['comment_id'])) {
            $comment_id = $_POST['comment_id'];
            $article_id = $_POST['article_id'];
            $result = $this->comment->delComment($article_id, $comment_id);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    public function __call($method, $args)
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
    }
}
