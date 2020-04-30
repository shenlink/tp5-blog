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
use app\index\model\Follow;
use app\index\model\Receive;

class User extends Base
{
    // 搜索相关操作的方法
    public function search(Request $request)
    {
        $type = '用户名查询结果';
        $username = $request->param('username');
        $username = '%' . $username . '%';
        $users = UserModel::where('username', 'like', $username)->paginate(5);
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        $this->view->assign('recommends', $recommends);
        $this->view->assign('type', $type);
        $this->view->assign('users', $users);
        return $this->view->fetch('public/search');
    }

    // 显示注册页面
    public function register()
    {
        return $this->view->fetch('register');
    }

    public function checkUsername(Request $request)
    {
        if ($request->isAjax()) {
            $username = trim($request->post('username'));
            $status = 0;
            $message = '用户名未注册';
            //如果在表中查询到该用户名
            if (UserModel::get(['username' => $username])) {
                $status = 1;
                $message = '用户名已注册';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 处理注册页面提交的数据
    public function checkRegister(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
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
        } else {
            return $this->error('非法访问');
        }
    }

    // 显示注册页面
    public function login()
    {
        return $this->view->fetch('login');
    }

    public function checkLogin(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '用户名或密码错误';
            $data = $request->post();
            $userStatus = UserModel::where('username', $data['username'])->value('status');
            if ($userStatus == 0) {
                $status = -1;
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
        } else {
            return $this->error('非法访问');
        }
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
        $recents = Article::where('author', $this->username)->field(['id', 'title'])->limit(5)->order('update_time', 'desc')->select();
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
        if ($request->isAjax()) {
            $data = $request->post();
            $condition = ['username' => $this->username];
            $result = UserModel::update($data, $condition);
            if ($result == true) {
                return ['status' => 1, 'message' => '修改成功'];
            } else {
                return ['status' => 0, 'message' => '修改失败'];
            }
        } else {
            return $this->error('非法访问');
        }
    }

    // 显示用户管理页面
    public function manage(Request $request)
    {
        $type = $request->param('type') ?? 'article';
        $articles = Article::where(['author' => $this->username])->order('create_time desc')->paginate(5);
        $comments = Comment::where(['username' => $this->username])->order('comment_time desc')->paginate(5);
        $fans = Follow::where(['author' => $this->username])->order('follow_time desc')->paginate(5);
        $follows = Follow::where(['username' => $this->username])->order('follow_time desc')->paginate(5);
        $receives = Receive::where(['username' => $this->username])->order('receive_time desc')->paginate(5);
        $this->view->assign('articles', $articles);
        $this->view->assign('comments', $comments);
        $this->view->assign('fans', $fans);
        $this->view->assign('follows', $follows);
        $this->view->assign('receives', $receives);
        $this->view->assign('type', $type);
        return $this->view->fetch('manage');
    }

    // 拉黑用户
    public function defriendUser(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '拉黑失败';
            $id = $request->post('id');
            $result = UserModel::update(['status' => 0], ['id' => $id]);
            if ($result == true) {
                $status = 1;
                $message = '拉黑成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 恢复用户的状态为正常
    public function normalUser(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '恢复失败';
            $id = $request->post('id');
            $result = UserModel::update(['status' => 1], ['id' => $id]);
            if ($result == true) {
                $status = 1;
                $message = '恢复成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 删除用户
    public function delUser(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $id = $request->post('id');
            UserModel::update(['is_delete' => 1, 'status' => -1], ['id' => $id]);
            $result = UserModel::destroy($id);
            if ($result == true) {
                $status = 1;
                $message = '删除成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    //恢复删除操作
    public function unDeleteAll(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '恢复失败';
            $admin = $request->post('username');
            if ($admin == $this->admin) {
                $result = UserModel::update(['delete_time' => NULL, 'is_delete' => 0], ['is_delete' => 1]);
                if ($result == true) {
                    $status = 1;
                    $message = '恢复成功';
                }
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    //恢复删除单个用户
    public function unDelete(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '恢复失败';
            $id = $request->post('id');
            $result = UserModel::update(['delete_time' => NULL, 'is_delete' => 0, 'status' => 1], ['is_delete' => 1, 'id' => $id]);
            if ($result == true) {
                $status = 1;
                $message = '恢复成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 每天新增的用户
    public function getNewUserCount()
    {
        // 当天新增多少个
        $format =  '"%H"';
        $result = UserModel::whereTime('create_time', 'today')->column("id,FROM_UNIXTIME(create_time, $format)");
        $result = array_count_values($result);
        $newPerHour = [];
        for ($i = 0; $i < 24; $i++) {
            $newPerHour[$i] = 0;
        }
        foreach ($newPerHour as $key => $value) {
            foreach ($result as $k => $v) {
                if ($key == $k) {
                    $newPerHour[$key] = $v;
                }
            }
        }
        $data = ['newPerHour' => $newPerHour];
        return json_encode($data);
    }

    // 显示个人页面
    public function user($username)
    {
        $userStatus = UserModel::where('username', $username)->value('status');
        if ($userStatus == 0) {
            return '用户不存在或正在拉黑状态';
        }
        $articles = Article::where(['author' => $username])->order('create_time desc')->paginate(5);
        $comments = Comment::where(['username' => $username])->order('comment_time desc')->paginate(5);
        $praises = Praise::where(['username' => $username])->order('praise_time desc')->paginate(5);
        $collects = Collect::where(['username' => $username])->order('collect_time desc')->paginate(5);
        $shares = Share::where(['username' => $username])->order('share_time desc')->paginate(5);
        $users = UserModel::get(['username' => $username]);
        $type = 'article';
        $praise_count = Praise::where('username', $this->username)->count();
        $comment_count = Comment::where('username', $this->username)->count();
        $recents = Article::where('author', $this->username)->field(['id', 'title'])->limit(5)->order('update_time', 'desc')->select();
        $followed = Follow::get(['username' => $this->username, 'author' => $username]);
        $this->view->assign('users', $users);
        $this->view->assign('articles', $articles);
        $this->view->assign('comments', $comments);
        $this->view->assign('praises', $praises);
        $this->view->assign('collects', $collects);
        $this->view->assign('shares', $shares);
        $this->view->assign('type', $type);
        $this->view->assign('praise_count', $praise_count);
        $this->view->assign('comment_count', $comment_count);
        $this->view->assign('followed', $followed);
        $this->view->assign('recents', $recents);
        return $this->view->fetch('user');
    }
}
