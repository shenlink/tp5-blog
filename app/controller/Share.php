<?php

namespace app\controller;

use core\lib\Controller;

class Share extends Controller
{

    // 显示404页面
    public function displayNone()
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
    }

    // 确认分享
    public function checkShare()
    {
        if (isset($_POST['article_id']) && isset($_POST['author']) && isset($_POST['title'])) {
            $article_id = $_POST['article_id'];
            $author = $_POST['author'];
            $title = $_POST['title'];
            $result =  $this->share->checkShare($article_id, $this->username);
            if ($result) {
                $cancel = $this->share->cancelShare($article_id, $this->username);
                echo $cancel ? '0' : '00';
            } else {
                $add = $this->share->addShare($article_id, $author, $title, $this->username, $this->time);
                echo $add ? '1' : '11';
            }
        } else {
            $this->displayNone();
        }
    }

    public function delShare()
    {
        if (isset($_POST['share_id']) && isset($_POST['article_id'])) {
            $article_id = $_POST['article_id'];
            $share_id = $_POST['share_id'];
            $result = $this->share->delShare($article_id,$share_id);
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