<?php

namespace app\controller;

use core\lib\Controller;

class Announcement extends Controller
{

    // 显示404页面
    public function displayNone()
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
    }

    // 修改公告
    public function changeAnnouncement($type, $announcement_id)
    {
        if($this->username == $this->admin) {
            $announcements = $this->announcement->getOneAnnouncement($announcement_id);
            $this->view->assign('announcements', $announcements);
            $this->view->assign('changeAnnouncement', 'changeAnnouncement');
            $this->view->display('change.html');
        }
    }

    // 删除公告
    public function delAnnouncement()
    {
        if (isset($_POST['announcement_id'])) {
            $announcement_id = $_POST['announcement_id'];
            $result = $this->announcement->delAnnouncement($announcement_id);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    // 添加页面，共有添加分类，公告功能
    public function addAnnouncement()
    {
        if ($_SESSION['username'] == $this->admin) {
            $recommends = $this->article->recommend();
            $this->view->assign('addAnnouncement', 'announcement');
            $this->view->assign('recommends', $recommends);
            $this->view->display('add.html');
        } else {
            $this->displayNone();
        }
    }

    // 确认添加
    public function checkAddAnnouncement()
    {
        if (isset($_POST['content'])) {
            $content = $_POST['content'];
            $result = $this->announcement->checkAddAnnouncement($content, $this->time);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    // 确认修改
    public function checkChangeAnnouncement()
    {
        if (isset($_POST['content']) && isset($_POST['announcement_id'])) {
            $content = $_POST['content'];
            $announcement_id = $_POST['announcement_id'];
            $result = $this->announcement->checkChangeAnnouncement($content, $announcement_id);
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
