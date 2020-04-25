<?php

namespace app\index\controller;

use app\index\controller\Base;

class User extends Base
{
    // 显示注册页面
    public function register()
    {
        return $this->view->fetch('register');
    }
}
