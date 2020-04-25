<?php

namespace app\controller;

use core\lib\Controller;

class Collect extends Controller
{

    // 显示404页面
    public function displayNone()
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
    }

    public function checkCollect()
    {
        if (isset($_POST['article_id']) && isset($_POST['author']) && isset($_POST['title'])) {
            $article_id = $_POST['article_id'];
            $author = $_POST['author'];
            $title = $_POST['title'];
            $result =  $this->collect->checkCollect($article_id, $this->username);
            if ($result) {
                $cancel = $this->collect->cancelCollect($article_id, $this->username);
                echo $cancel ? '0' : '00';
            } else {
                $add = $this->collect->addCollect($article_id, $author, $title, $this->username, $this->time);
                echo $add ? '1' : '11';
            }
        } else {
            $this->displayNone();
        }
    }

    // 删除收藏
    public function delCollect()
    {
        if (isset($_POST['collect_id']) && isset($_POST['article_id'])) {
            $article_id = $_POST['article_id'];
            $collect_id = $_POST['collect_id'];
            $result = $this->collect->delCollect($article_id,$collect_id );
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