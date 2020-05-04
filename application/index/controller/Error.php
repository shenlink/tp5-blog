<?php

namespace app\index\controller;

// 当用户访问不存在的控制器时，定位到这个类
class Error
{
    // 当用户访问不存在的控制器时，定位到这个类的这个方法
    public function index()
    {
        return 'url地址出错了';
    }

    // 当用户访问不存在的控制器并且还访问方法时，定位到这个类的这个方法
    public function _empty()
    {
        return 'url地址出错了';
    }
}
