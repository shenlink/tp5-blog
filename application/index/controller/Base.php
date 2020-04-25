<?php

namespace app\index\controller;

use think\Controller;
use think\Session;

class Base extends Controller
{
    // 重写初始化方法
    protected function _initialize()
    {
        parent::_initialize();
        define('USERNAME', Session::has('username') ? Session::get('username') : null);
        $username = USERNAME ?? null;
        $this->view->assign('username', $username);
    }

    // 判断用户是否登录
    protected function isLogin()
    {
        if (is_null(USERNAME)) {
            $this->error('用户未登录', 'user/login');
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
