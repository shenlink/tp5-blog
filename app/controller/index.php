<?php

namespace app\controller;

use core\lib\Controller;

class Index extends Controller
{

    public function displayNone()
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
        exit();
    }

    // 显示首页
    public function index($type = 'pagination', $pagination = 1)
    {
        $announcements = $this->announcement->getAnnouncement();
        $data = $this->article->getIndexArticle($pagination, 5, $type);
        $articles = $data['items'];
        $articlePage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
        $recommends = $this->article->recommend();
        $this->view->assign('announcements', $announcements);
        $this->view->assign('articles', $articles);
        $this->view->assign('articlePage', $articlePage);
        $this->view->assign('recommends', $recommends);
        $this->view->display('index.html');
    }

    public function __call($method, $args)
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
    }
}
