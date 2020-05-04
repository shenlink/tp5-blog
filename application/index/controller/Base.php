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
        // 实现父类的_initialize方法
        parent::_initialize();
        // 定义USERNAME常量，用于存储用户名
        define('USERNAME', Session::has('username') ? Session::get('username') : null);
        $this->username = USERNAME ?? null;
        // 获取配置的管理员用户名
        $this->admin = config('admin');
        // 获取所有分类
        $categorys = Category::all();
        // 模板赋值
        $this->view->assign('username', $this->username);
        $this->view->assign('admin', $this->admin);

        $this->view->assign('categorys', $categorys);
    }

    // 判断用户是否登录
    protected function isLogin()
    {
        if (is_null(USERNAME)) {
            return $this->error('用户未登录', '/user/login');
        }
    }

    // 用户已经登录，不能再重复登录
    protected function alreadyLogin()
    {
        if (USERNAME) {
            return $this->error('用户已经登陆,请勿重复登陆');
        }
    }

    // 当用户不是管理员时
    protected function isNotAdmin()
    {
        return $this->admin != $this->username;
    }
}
