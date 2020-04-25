<?php

namespace app\controller;

use core\lib\Controller;

class Praise extends Controller
{

    // 显示404页面
    public function displayNone()
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
    }

    // 确认点赞
    public function checkPraise()
    {
        if (isset($_POST['article_id']) && isset($_POST['author']) && isset($_POST['title'])) {
            $article_id = $_POST['article_id'];
            $author = $_POST['author'];
            $title = $_POST['title'];
            $result =  $this->praise->checkPraise($article_id, $this->username);
            if ($result) {
                $cancel = $this->praise->cancelPraise($article_id, $this->username);
                echo $cancel ? '0' : '00';
            } else {
                $add = $this->praise->addPraise($article_id, $author, $title, $this->username, $this->time);
                echo $add ? '1' : '11';
            }
        } else {
            $this->displayNone();
        }
    }

    public function delPraise()
    {
        if (isset($_POST['article_id']) && isset($_POST['praise_id'])) {
            $article_id = $_POST['article_id'];
            $praise_id = $_POST['praise_id'];
            $result = $this->praise->delPraise($article_id, $praise_id);
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
