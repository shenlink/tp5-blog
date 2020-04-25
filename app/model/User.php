<?php

namespace app\model;

use core\lib\Model;

class User extends Model
{
    private static $user;
    public static function getInstance()
    {
        if (self::$user) {
            return self::$user;
        } else {
            self::$user = new self();
            return self::$user;
        }
    }

    public function search($username, $currentPage = 1, $pageSize)
    {
        $content = '%' . $username . '%';
        return $this->table('user')->field('username,introduction,created_at,article_count,follow_count,fans_count')->where("username like \"{$content}\"")->pages($currentPage, $pageSize, "/user/search", $username);

    }

    // 验证用户名是否被注册
    public function checkUsername($username)
    {
        return $this->table('user')->field('username')->where(['username' => "{$username}"])->select();
    }

    // 处理注册操作
    public function checkRegister($username, $password, $created_at)
    {
        return $this->table('user')->insert(['username' => "{$username}", 'password' => "{$password}", 'created_at' => "{$created_at}"]);
    }

    // 确认用户的状态
    public function checkStatus($username)
    {
        return $this->table('user')->where(['username' => "{$username}", 'status' => 1])->select();
    }

    // 处理登录操作
    public function checkLogin($username, $password)
    {
        return $this->table('user')->where(['username' => "{$username}", 'password' => "{$password}"])->select();
    }

    // 获取个人页面的用户信息
    public function personal($username)
    {
        return $this->table('user')->field('username,introduction,article_count,follow_count,fans_count,created_at')->where(['username' => "{$username}"])->select();

    }

    // 处理用户在个人信息修改页面提交的数据
    public function checkChange($username, $password, $introduction)
    {
        return $this->table('user')->where(['username' => "{$username}"])->update(['password' => "{$password}", 'introduction' => "{$introduction}"]);
    }

    // 处理管理员拉黑用户
    public function defriendUser($user_id)
    {
        return $this->table('user')->where(['user_id' => "{$user_id}"])->update(['status' => 0]);
    }

    // 处理管理员恢复用户的状态到正常
    public function normalUser($user_id)
    {
        return $this->table('user')->where(['user_id' => "{$user_id}"])->update(['status' => 1]);
    }

    // 处理管理员删除用户
    public function delUser($user_id)
    {
        return $this->table('user')->where(['user_id' => "{$user_id}"])->delete();
    }

    public function getAllUser($currentPage = 1, $pageSize)
    {
        return $this->table('user')->field('user_id,username,role,article_count,follow_count,fans_count,status,created_at')->pages($currentPage, $pageSize, '/admin/manage', 'user');

    }
}
