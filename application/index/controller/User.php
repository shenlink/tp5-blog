<?php

namespace app\index\controller;

use think\Request;
use think\Session;
use app\index\controller\Base;
use app\index\model\Article;
use app\index\model\Collect;
use app\index\model\Comment;
use app\index\model\Follow;
use app\index\model\Praise;
use app\index\model\Share;
use app\index\model\User as UserModel;


class User extends Base
{
    // 搜索相关操作的方法
    public function search(Request $request)
    {
        // 标志位，会在页头显示
        $type = '用户名查询结果';
        // 获取查询条件
        $username = $request->param('username');
        // 使用模糊查询
        $username = '%' . $username . '%';
         // 获取符合搜索结果的用户并分页
        $users = UserModel::where('username', 'like', $username)->paginate(5);
        // 获取10篇推荐文章
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        // 模板赋值
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

    // 确认用户名，在注册的时候，用户输入用户名之后，输入框失去焦点就访问这个方法
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
            // 验证用户输入
            $message = $this->validate($data, $rule);
            // 如果验证通过
            if ($message === true) {
                // 添加用户记录
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

    // 确认登录
    public function checkLogin(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '用户名或密码错误';
            $data = $request->post();
            // 判断用户的状态
            $userStatus = UserModel::where('username', $data['username'])->value('status');
            // 状态为0是拉黑状态
            if ($userStatus == 0) {
                $status = -1;
                return ['status' => $status, 'message' => $message];
            }
            //验证规则
            $rule = [
                'username|用户名' => 'require|min:4|max:16',
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
        // 判断是否登录，若没有，就定位到错误页面
        $this->isLogin();
        // 获取10篇推荐文章
        $recents = Article::where('author', $this->username)->field(['id', 'title'])->limit(5)->order('update_time', 'desc')->select();
        // 获取当前用户的所有信息
        $users = UserModel::all(['username' => $this->username]);
        // 获取当前用户的获赞总数
        $praise_count = Praise::where('author', $this->username)->count();
        // 获取当前用户获得的文章获得评论的总数
        $comment_count = Comment::where('author', $this->username)->count();
        // 模板赋值
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
            // 更新用户信息
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

    // 拉黑用户
    public function defriendUser(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '拉黑失败';
            $id = $request->post('id');
            // 拉黑用户
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
            // 恢复用户的状态为正常
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
            // 先更新删除标志位，ststus为-1代表删除状态
            UserModel::update(['is_delete' => 1, 'status' => -1], ['id' => $id]);
            // 软删除用户
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
                // 恢复软删除的数据
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
            // 恢复软删除的单个用户
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

    // 新增的用户
    public function getNewUserCount(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
            $time = $data['time'];
            $format = $data['format'];
            // 新增多少个
            $result = UserModel::whereTime('create_time', $time)->column("id,FROM_UNIXTIME(create_time, $format)");
            // 计算$result的不重复的值的总数，比如14点的值是2
            $result = array_count_values($result);
            $newPerTime = [];
            if ($format == '"%k"') {
                // 获取一个一维数组，表示一天
                for ($i = 1; $i < 25; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, 24);
                $type = 'hour';
            } else if ($format == '"%e"') {
                $days = date("t") + 1;
                // 获取一个一维数组，表示一月
                for ($i = 1; $i < $days; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, $days - 1);
                $type = 'day';
            } else {
                // 获取一个一维数组，表示一年
                for ($i = 1; $i < 13; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, 12);
                $type = 'month';
            }
            // 循环遍历$newPerTime
            foreach ($newPerTime as $key => $value) {
                foreach ($result as $k => $v) {
                    // 当$newPerTime的key与$result的key相同，比如14==14,14点就有2个用户
                    if ($key == $k) {
                        $newPerTime[$key] = $v;
                    }
                }
            }
            // type是时间范围
            $data = ['type' => $type, 'rangeTime' => $rangeTime, 'newPerTime' => $newPerTime];
            return json_encode($data);
        } else {
            return $this->error('非法访问');
        }
    }

    // 显示个人页面
    public function user($username)
    {
        // 获取用户的状态
        $userStatus = UserModel::where('username', $username)->value('status');
        // 如果用户状态为0
        if ($userStatus == 0) {
            return '用户不存在或正在拉黑状态';
        }
        // 获取当前用户的所有文章并分页
        $articles = Article::where(['author' => $username])->order('create_time desc')->paginate(5);
        // 获取当前用户的所有评论记录并分页
        $comments = Comment::where(['username' => $username])->order('comment_time desc')->paginate(5);
        // 获取当前用户的所有点赞记录并分页
        $praises = Praise::where(['username' => $username])->order('praise_time desc')->paginate(5);
        // 获取当前用户的所有收藏记录并分页
        $collects = Collect::where(['username' => $username])->order('collect_time desc')->paginate(5);
        // 获取当前用户的所有分享记录并分页
        $shares = Share::where(['username' => $username])->order('share_time desc')->paginate(5);
        // 获取当前用户的所有个人信息
        $users = UserModel::get(['username' => $username]);
        // 标志位
        $type = 'article';
        // 获取当前用户的文获取的点赞的总数
        $praise_count = Praise::where('author', $this->username)->count();
        // 获取当前用户的文章获得的评论的总数
        $comment_count = Comment::where('author', $this->username)->count();
        // 获取10篇推荐文章
        $recents = Article::where('author', $this->username)->field(['id', 'title'])->limit(5)->order('update_time', 'desc')->select();
        // 判断当前的登录用户是否关注了这个用户
        $followed = Follow::get(['username' => $this->username, 'author' => $username]);
        // 模板赋值
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
