<?php

namespace app\controller;

use core\lib\Controller;

class Category extends Controller
{

    // 显示404页面
    public function displayNone()
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
        exit();
    }

    // 添加页面，共有添加分类，公告功能
    public function addCategory()
    {
        if ($this->username == $this->admin) {
            $addCategory = 'addCategory';
            $recommends = $this->article->recommend();
            $this->view->assign('addCategory', $addCategory);
            $this->view->assign('recommends', $recommends);
            $this->view->display('add.html');
        } else {
            $this->displayNone();
        }
    }

    // 确认添加
    public function checkAddCategory()
    {
        if (isset($_POST['categoryName'])) {
            $categoryName = $_POST['categoryName'];
            $result = $this->category->addCategory($categoryName);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    public function __call($method, $args)
    {
        $categoryName = $method;
        $realCategory = $this->category->checkCategory($categoryName);
        if (!$realCategory) {
            $this->view->assign('error', 'error');
            $this->view->display('error.html');
            exit();
        }
        $pagination = $args[1] ?? 1;
        $data = $this->article->getCategoryArticle($categoryName, $pagination, 5);
        $articles = $data['items'];
        $articlePage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
        $this->view->assign('articlePage', $articlePage);
        $recommends = $this->article->recommend();
        $this->view->assign('articles', $articles);
        $this->view->assign('categoryName', $categoryName);
        $this->view->assign('recommends', $recommends);
        $this->view->display('category.html');
    }
}
