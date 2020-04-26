<?php

namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use app\index\model\User as UserModel;
use think\Session;
use app\index\model\Article;
use app\index\model\Praise;
use app\index\model\Collect;
use app\index\model\Share;
use app\index\model\Comment;

class User extends Base
{
    // 显示注册页面
    public function register()
    {
        return $this->view->fetch('register');
    }

    public function checkUsername(Request $request)
    {
        $username = trim($request->param('username'));
        $status = 0;
        $message = '用户名未注册';
        //如果在表中查询到该用户名
        if (UserModel::get(['username' => $username])) {
            $status = 1;
            $message = '用户名已注册';
        }
        return ['status' => $status, 'message' => $message];
    }

    // 处理注册页面提交的数据
    public function checkRegister(Request $request)
    {
        $data = $request->param();
        $status = 0;
        $message = '注册失败';
        //验证规则
        $rule = [
            'username|姓名' => 'require|min:4|max:16',
            'password|密码' => 'require|min:6|max:16',
            'captcha|验证码' => 'require|captcha'
        ];
        $message = $this->validate($data, $rule);
        if ($message === true) {
            $user = UserModel::create($request->only(['username', 'password']));
            if ($user === null) {
                $status = 0;
                $message = '注册失败';
            } else {
                $status = 1;
                $message = '注册成功';
            }
        }
        return ['status' => $status, 'message' => $message];
    }

    // 显示注册页面
    public function login()
    {
        return $this->view->fetch('login');
    }

    public function checkLogin(Request $request)
    {
        $status = -1;
        $message = '用户处于拉黑状态';
        $data = $request->param();
        $userStatus = UserModel::where('username', $data['username'])->value('status');
        if ($userStatus == 0) {
            return ['status' => $status, 'message' => $message];
        }
        //验证规则
        $rule = [
            'username|用户名' => 'require|min:4|max:16',
            'password|密码' => 'require|min:6|max:16',
            'captcha|验证码' => 'require|captcha'
        ];
        $message = $this->validate($data, $rule);
        //通过验证后,进行数据表查询
        if ($message === true) {
            $condition = [
                'username' => $data['username'],
                'password' => md5($data['password'])
            ];
            $user = UserModel::get($condition);
            if ($user === null) {
                $status = 0;
                $message = '用户名或密码错误';
            } else {
                $status = 1;
                $message = '登录成功';
                Session::set('username', $user->username);
            }
        }
        return ['status' => $status, 'message' => $message];
    }

    //退出登录
    public function logout()
    {
        // 删除session
        Session::delete('username');
        $this->success('退出登录,正在返回', url('user/login'));
    }

    // 显示用户修改密码和个人简介的页面
    public function change()
    {
        $this->isLogin();
        $recents = Article::where('author', $this->username)->field(['article_id', 'title'])->limit(5)->order('update_time', 'desc')->select();
        $users = UserModel::all(['username' => $this->username]);
        $praise_count = Praise::where('username', $this->username)->count();
        $comment_count = Comment::where('username', $this->username)->count();
        $this->view->assign('title', '个人信息修改');
        $this->view->assign('changeUser', 'changeUser');
        $this->view->assign('praise_count', $praise_count);
        $this->view->assign('comment_count', $comment_count);
        $this->view->assign('recents', $recents);
        $this->view->assign('users', $users[0]);
        return $this->view->fetch('change');
    }

    // 处理从修改页面提交的数据
    public function checkChange(Request $request)
    {
        $data = $request->param();
        $condition = ['username' => $data['username']];
        $result = UserModel::update($data, $condition);
        if ($result == true) {
            return ['status' => 1, 'message' => '修改成功'];
        } else {
            return ['status' => 0, 'message' => '修改失败'];
        }
    }

    // 显示个人页面
    public function _empty($name)
    {
        $userStatus = UserModel::where('username', $name)->value('status');
        if ($userStatus == 0) {
            return '用户正在拉黑状态';
        }
        $articles = Article::all(['author' => $name]);
        $comments = Comment::all(['username' => $name]);
        $praises = Praise::all(['username' => $name]);
        $collects = Collect::all(['username' => $name]);
        $shares = Share::all(['username' => $name]);
        $users = UserModel::all(['username' => $name]);
        $type = 'article';
        $praise_count = Praise::where('username', $this->username)->count();
        $comment_count = Comment::where('username', $this->username)->count();
        $recents = Article::where('author', $this->username)->field(['article_id', 'title'])->limit(5)->order('update_time', 'desc')->select();
        $this->view->assign('users', $users[0]);
        $this->view->assign('articles', $articles);
        $this->view->assign('comments', $comments);
        $this->view->assign('praises', $praises);
        $this->view->assign('collects', $collects);
        $this->view->assign('shares', $shares);
        $this->view->assign('type', $type);
        $this->view->assign('praise_count', $praise_count);
        $this->view->assign('comment_count', $comment_count);
        $this->view->assign('recents', $recents);
        return $this->view->fetch('user');
    }
}
