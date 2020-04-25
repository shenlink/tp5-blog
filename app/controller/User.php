<?php

namespace app\controller;

use core\lib\Controller;

class User extends Controller
{

    // 显示404页面
    public function displayNone()
    {
        $this->view->assign('error', 'error');
        $this->view->display('error.html');
        exit();
    }

    // 搜索相关操作的方法
    public function search($username, $pagination)
    {
        $type = '用户名查询结果';
        $data = $this->user->search($username, $pagination, 5);
        $users = $data['items'];
        $userPage = $data['pageHtml'];
        $recommends = $this->article->recommend();
        $this->view->assign('recommends', $recommends);
        $this->view->assign('type', $type);
        $this->view->assign('users', $users);
        $this->view->assign('userPage', $userPage);
        $this->view->display('search.html');
    }

    // 确认用户名，在用户注册的时候，在用户名输入框输入后失去焦点时触发ajax，访问该方法
    public function checkUsername()
    {
        if (isset($_POST['username'])) {
            $username = $_POST['username'];
            $result =  $this->user->checkUsername($username);
            $intercept = $this->getIntercept($username);
            echo $result || $intercept ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    public function getIntercept($username)
    {
        $pattern = '/displayNone|null|guest|search|checkUsername|getIntercept|register|login|logout|checkRegister|checkLogin|write|checkWrite|change|checkChange|manage|editArticle|checkEdit|__call/i';
        $intercept = preg_match($pattern, $username);
        return $intercept;
    }

    // 显示注册页面
    public function register()
    {
        $this->view->display('register.html');
    }

    // 处理注册页面提交的数据
    public function checkRegister()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = trim($_POST['username']);
            $password = md5(trim($_POST['password']));
            $result = $this->user->checkRegister($username, $password, $this->time);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    // 显示登录页面
    public function login()
    {
        if (isset($_SESSION['username'])) {
            echo '<script>alert("你已经登录了");window.location.href="/";</script>';
        } else {
            $this->view->display('login.html');
        }
    }

    // 处理登录页面提交的数据
    public function checkLogin()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = trim($_POST['username']);
            $password = md5(trim($_POST['password']));
            $status = $this->user->checkStatus($username);
            if (!$status) {
                echo '-1';
                exit();
            }
            $result = $this->user->checkLogin($username, $password);
            if ($result) {
                session_start();
                $_SESSION['username'] = $username;
                echo '1';
            } else {
                echo '0';
            }
        } else {
            $this->displayNone();
        }
    }

    // 退出登录功能的实现
    public function logout()
    {
        if ($this->username) {
            unset($_SESSION['username']);
            echo "<script>window.location.href='/';</script>";
        } else {
            $this->view->assign('nologin', 'nologin');
            $this->view->display('error.html');
        }
        var_dump($this->username);
    }

    // 显示用户修稿密码和个人简介的页面
    public function change()
    {
        if ($this->username) {
            $recents = $this->article->getRecentArticle($this->username);
            $users = $this->user->personal($this->username);
            $praise_count = $this->praise->getPraiseCount($this->username);
            $comment_count = $this->comment->getCommentCount($this->username);
            $this->view->assign('changeUser', 'changeUser');
            $this->view->assign('praise_count', $praise_count);
            $this->view->assign('comment_count', $comment_count);
            $this->view->assign('recents', $recents);
            $this->view->assign('users', $users);
            $this->view->display('change.html');
        } else {
            $this->view->assign('nologin', 'nologin');
            $this->view->display('error.html');
        }
    }

    // 处理从修稿页面提交的数据
    public function checkChange()
    {
        if (isset($_POST['password']) && isset($_POST['introduction'])) {
            $introduction = trim($_POST['introduction']);
            $password = md5(trim($_POST['password']));
            $result = $this->user->checkChange($this->username, $password, $introduction);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    // 显示用户管理页面
    public function manage($type = 'article', $pagination = 1)
    {
        if ($this->username) {
            $articlePages = $type == 'article' ? $pagination : 1;
            $data = $this->article->getManageArticle($this->username, $articlePages, 5);
            $articles = $data['items'];
            $articlePage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
            $this->view->assign('articlePage', $articlePage);

            $commentPages = $type == 'comment' ? $pagination : 1;
            $data = $this->comment->getManageComment($this->username, $commentPages, 5);
            $comments = $data['items'];
            $commentPage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
            $this->view->assign('commentPage', $commentPage);

            $followPages = $type == 'follow' ? $pagination : 1;
            $data = $this->follow->getFollow($this->username, $followPages, 5);
            $follows = $data['items'];
            $followPage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
            $this->view->assign('followPage', $followPage);

            $fnasPages = $type == 'fans' ? $pagination : 1;
            $data = $this->follow->getFans($this->username, $fnasPages, 5);
            $fans = $data['items'];
            $fnasPage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
            $this->view->assign('fnasPage', $fnasPage);

            $receivePages = $type == 'receive' ? $pagination : 1;
            $data = $this->receive->getReceive($this->username, $receivePages, 5);
            $receives = $data['items'];
            $receivePage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
            $this->view->assign('receivePage', $receivePage);
            $this->view->assign('username', $this->username);
            $this->view->assign('articles', $articles);
            $this->view->assign('comments', $comments);
            $this->view->assign('follows', $follows);
            $this->view->assign('fans', $fans);
            $this->view->assign('receives', $receives);
            $this->view->assign('type', $type);
            $this->view->display('manage.html');
        } else {
            $this->view->assign('nologin', 'nologin');
            $this->view->display('error.html');
        }
    }

    // 拉黑用户
    public function defriendUser()
    {
        if (isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
            $result = $this->user->defriendUser($user_id);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    // 恢复用户的状态为正常
    public function normalUser()
    {
        if (isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
            $result = $this->user->normalUser($user_id);
            echo $result ? '1' : '0';
        } else {
            $this->displayNone();
        }
    }

    // 删除用户
    public function delUser()
    {
        if (isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
            $result = $this->user->delUser($user_id);
            if ($result) {
                echo '1';
            } else {
                echo '0';
            }
        } else {
            $this->displayNone();
        }
    }

    // 当用户在URL中输入/user/之后的是用户名时，访问该用户
    public function __call($method, $args)
    {

        $author = $method;
        $status = $this->user->checkStatus($author);
        if (!$status) {
            $this->view->assign('error', 'error');
            $this->view->display('error.html');
            exit();
        }
        $type = $args[0] ?? 'article';
        $pagination = $args[1] ?? 1;
        $articlePages = $type == 'article' ? $pagination : 1;
        $data = $this->article->getUserArticle($author, $articlePages, 5);
        $articles = $data['items'];
        $articlePage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
        $this->view->assign('articlePage', $articlePage);

        $collectPages = $type == 'collect' ? $pagination : 1;
        $data = $this->collect->getCollect($author, $collectPages, 5);
        $collects = $data['items'];
        $collectPage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
        $this->view->assign('collectPage', $collectPage);

        $commentPages = $type == 'comment' ? $pagination : 1;
        $data = $this->comment->getComment($author, $commentPages, 5);
        $comments = $data['items'];
        $commentPage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
        $this->view->assign('commentPage', $commentPage);

        $praisePages = $type == 'praise' ? $pagination : 1;
        $data = $this->praise->getPraise($author, $praisePages, 5);
        $praises = $data['items'];
        $praisePage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
        $this->view->assign('praisePage', $praisePage);

        $sharePages = $type == 'share' ? $pagination : 1;
        $data = $this->share->getShare($author, $sharePages, 5);
        $shares = $data['items'];
        $sharePage = $data['pageHtml'] != 'error' ? $data['pageHtml'] : $this->displayNone();
        $this->view->assign('sharePage', $sharePage);
        if ($this->username) {
            $follows = $this->follow->checkFollow($author, $this->username);
        }
        $users = $this->user->personal($author);
        $praise_count = $this->praise->getPraiseCount($author);
        $comment_count = $this->comment->getCommentCount($author);
        $recents = $this->article->getRecentArticle($author);
        $this->view->assign('articles', $articles);
        $this->view->assign('collects', $collects);
        $this->view->assign('comments', $comments);
        $this->view->assign('follows', $follows);
        $this->view->assign('praise_count', $praise_count);
        $this->view->assign('comment_count', $comment_count);
        $this->view->assign('praises', $praises);
        $this->view->assign('recents', $recents);
        $this->view->assign('shares', $shares);
        $this->view->assign('type', $type);
        $this->view->assign('users', $users);
        $this->view->display('user.html');
    }
}
