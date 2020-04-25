<?php

namespace app\controller;

use core\lib\Controller;

class Message extends Controller
{

    // 显示404页面
    public function displayNone()
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
    }

    public function addMessage($type, $username)
    {
        $normalUsername = $this->user->checkStatus($username);
        if (!$normalUsername) {
            $this->displayNone();
            exit();
        }
        if ($this->username == $this->admin) {
            $recommends = $this->article->recommend();
            $this->view->assign('author', $username);
            $this->view->assign('recommends', $recommends);
            $this->view->display('add.html');
        } else {
            $this->displayNone();
        }
    }

    // 处理私信数据
    public function checkAddMessage()
    {
        if (isset($_POST['author']) && isset($_POST['content'])) {
            $author = $_POST['author'];
            $content = $_POST['content'];
            $result = $this->message->checkAddMessage($author, $content, $this->time);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    // 删除私信
    public function delMessage()
    {
        if (isset($_POST['message_id'])) {
            $message_id = $_POST['message_id'];
            $result = $this->message->delMessage($message_id);
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
