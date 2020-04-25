<?php

namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use app\index\model\User as UserModel;
use think\Session;

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
        $status = UserModel::where('username', $data['username'])->value('status');
        if ($status == 0) {
            $status = -1;
            $message = '用户处于拉黑状态';
            return ['status' => $status, 'message' => $message];
        }
        //验证规则
        $rule = [
            'username|用户名' => 'require',
            'password|密码' => 'require',
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
}
