<?php

namespace app\index\controller;

use think\Controller;
use think\Session;
use app\index\model\Category;


class Base extends Controller
{
    // 重写初始化方法
    protected function _initialize()
    {
        parent::_initialize();
        define('USERNAME', Session::has('username') ? Session::get('username') : null);
        $this->username = USERNAME ?? null;
        $this->admin = config('admin');
        $this->view->assign('username', $this->username);
        $this->view->assign('admin', $this->admin);
        $categorys = Category::all();
        $this->view->assign('categorys', $categorys);
    }

    // 判断用户是否登录
    protected function isLogin()
    {
        if (is_null(USERNAME)) {
            $this->error('用户未登录', '/user/login');
        }
    }

    // 用户已经登录
    protected function alreadyLogin()
    {
        if (USERNAME) {
            $this->error('用户已经登陆,请勿重复登陆', 'index/index');
        }
    }
}
