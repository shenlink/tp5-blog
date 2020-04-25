<?php

namespace app\controller;

use core\lib\Controller;


class Article extends Controller
{

    // 显示404页面
    public function displayNone()
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
    }

    public function search($condition, $pagination)
    {
        $type = '文章查询结果';
        $condition = urldecode($condition);
        $data = $this->article->search($condition, $pagination, 5);
        $articles = $data['items'];
        $articlePage = $data['pageHtml'];
        $recommends = $this->article->recommend();
        $this->view->assign('articles', $articles);
        $this->view->assign('articlePage', $articlePage);
        $this->view->assign('recommends', $recommends);
        $this->view->assign('type', $type);
        $this->view->display('search.html');
    }

    // 显示写文章页面
    public function write()
    {
        if ($this->username) {
            $this->view->display('write.html');
        } else {
            $this->view->assign('nologin', 'nologin');
            $this->view->display('error.html');
        }
    }

    // 处理写文章页面提交的数据
    public function checkWrite()
    {
        if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['category'])) {
            $category = $_POST['category'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $result = $this->article->checkWrite($this->username, $category, $title,  $content,  $this->time);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    // 显示编辑文章页面
    public function editArticle($type, $article_id)
    {
        $author = $this->article->getAuthor($article_id);
        $author = $author['author'];
        if (!($author == $_SESSION['username'])) {
            $this->displayNone();
            exit();
        }
        $articles = $this->article->getEditArticle($article_id);
        $this->view->assign('articles', $articles);
        $this->view->display('edit.html');
    }

    // 处理文章编辑页面提交的数据
    public function checkEdit()
    {
        if (isset($_POST['article_id']) && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['category'])) {
            $article_id = $_POST['article_id'];
            $category = $_POST['category'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $result = $this->article->checkEdit($article_id, $category, $title, $content, $this->time);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    // 拉黑文章
    public function defriendArticle()
    {
        if (isset($_POST['article_id'])) {
            $article_id = $_POST['article_id'];
            $result = $this->article->defriendArticle($article_id);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    public function normalArticle()
    {
        if (isset($_POST['article_id'])) {
            $article_id = $_POST['article_id'];
            $result = $this->article->normalArticle($article_id);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    //删除文章
    public function delArticle()
    {
        if (isset($_POST['article_id']) && isset($_POST['category'])) {
            $article_id = $_POST['article_id'];
            $category = $_POST['category'];
            $result = $this->article->delArticle($article_id, $this->username,$category);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    public function __call($method, $args)
    {
        $article_id = $method;
        if (!is_numeric($article_id)) {
            $this->view->assign('error', 'error');
            $this->view->display('error.html');
            exit();
        }
        $realArticle_id = $this->article->checkArticleId($article_id);
        if ($realArticle_id) {
            $articles = $this->article->getArticle($article_id);
            $comments = $this->comment->getArticleComment($article_id);
            $author = $this->article->getAuthor($article_id);
            $author = $author['author'];
            $users = $this->user->personal($author);
            if ($this->username) {
                $follows = $this->follow->checkFollow($author, $this->username);
                $praised =  $this->praise->checkPraise($article_id, $this->username);
                $collected =  $this->collect->checkCollect($article_id, $this->username);
                $shared =  $this->share->checkShare($article_id, $this->username);
            }
            $recents = $this->article->getRecentArticle($author);
            $praise_count = $this->praise->getPraiseCount($author);
            $comment_count = $this->comment->getCommentCount($author);
            $this->view->assign('articles', $articles);
            $this->view->assign('collected', $collected);
            $this->view->assign('comments', $comments);
            $this->view->assign('follows', $follows);
            $this->view->assign('praised', $praised);
            $this->view->assign('praise_count', $praise_count);
            $this->view->assign('comment_count', $comment_count);
            $this->view->assign('recents', $recents);
            $this->view->assign('shared', $shared);
            $this->view->assign('users', $users);
            $this->view->display('article.html');
        } else {
            $this->view->assign('error', 'error');
            $this->view->display('error.html');
        }
    }
}
